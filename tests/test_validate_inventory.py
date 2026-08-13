"""Unit tests for tools/validate-inventory.py.

These tests cover the behaviour introduced/altered by moving the ``subprocess``
import to module scope (instead of a local import inside the ``try`` block in
``main()``) and adding the descriptive docstring to ``main()``. Because the
script file name contains a hyphen it cannot be imported with a normal
``import`` statement, so it is loaded dynamically via ``importlib``.

Run with:
    python3 -m unittest tests.test_validate_inventory -v
"""

import importlib.util
import inspect
import io
import json
import os
import unittest
from contextlib import redirect_stdout
from unittest.mock import MagicMock, mock_open, patch

REPO_ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MODULE_PATH = os.path.join(REPO_ROOT, "tools", "validate-inventory.py")

_spec = importlib.util.spec_from_file_location("validate_inventory", MODULE_PATH)
validate_inventory = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(validate_inventory)


class ValidateInventorySubprocessImportTest(unittest.TestCase):
    """Guards the relocation of ``import subprocess`` to module scope."""

    def test_subprocess_is_available_as_a_module_level_attribute(self):
        self.assertTrue(hasattr(validate_inventory, "subprocess"))
        self.assertEqual(validate_inventory.subprocess.__name__, "subprocess")

    def test_module_source_imports_subprocess_at_top_level(self):
        with open(MODULE_PATH, "r", encoding="utf-8") as handle:
            source = handle.read()
        self.assertIn("import subprocess", source)

    def test_main_function_source_no_longer_imports_subprocess_locally(self):
        main_source = inspect.getsource(validate_inventory.main)
        self.assertNotIn("import subprocess", main_source)


class ValidateInventoryMainDocstringTest(unittest.TestCase):
    """Guards the new docstring added to ``main()``."""

    def test_main_has_a_non_empty_docstring(self):
        self.assertIsNotNone(validate_inventory.main.__doc__)
        self.assertNotEqual(validate_inventory.main.__doc__.strip(), "")

    def test_main_docstring_describes_its_purpose_and_exit_codes(self):
        doc = validate_inventory.main.__doc__
        self.assertIn("Validate inventory identity variables", doc)
        self.assertIn("Podman CMS", doc)
        self.assertIn("Exits with status 1", doc)
        self.assertIn("status 0", doc)


class ValidateInventoryMainBehaviourTest(unittest.TestCase):
    """Regression coverage for main() to ensure the import relocation did not
    change its runtime behaviour."""

    def _ansible_success_payload(self, overrides=None):
        payload_vars = dict(validate_inventory.required)
        if overrides:
            payload_vars.update(overrides)
        return json.dumps({"all": {"vars": payload_vars}, "_meta": {"hostvars": {}}})

    def test_main_exits_zero_when_ansible_inventory_matches_required_identities(self):
        fake_result = MagicMock(returncode=0, stdout=self._ansible_success_payload(), stderr="")
        with patch.object(validate_inventory.shutil, "which", return_value="/usr/bin/ansible-inventory"), \
                patch.object(validate_inventory.subprocess, "run", return_value=fake_result):
            buf = io.StringIO()
            with redirect_stdout(buf):
                with self.assertRaises(SystemExit) as raised:
                    validate_inventory.main([])
        self.assertEqual(raised.exception.code, 0)
        self.assertIn("Sovereign Gate validation complete", buf.getvalue())

    def test_main_exits_one_when_ansible_inventory_reports_a_conflicting_identity(self):
        fake_result = MagicMock(
            returncode=0,
            stdout=self._ansible_success_payload({"podman_cms_user": "root"}),
            stderr="",
        )
        with patch.object(validate_inventory.shutil, "which", return_value="/usr/bin/ansible-inventory"), \
                patch.object(validate_inventory.subprocess, "run", return_value=fake_result):
            buf = io.StringIO()
            with redirect_stdout(buf):
                with self.assertRaises(SystemExit) as raised:
                    validate_inventory.main([])
        self.assertEqual(raised.exception.code, 1)
        self.assertIn("Identity Standard violation", buf.getvalue())
        self.assertIn("podman_cms_user", buf.getvalue())

    def test_main_exits_one_when_no_variables_can_be_loaded_from_any_source(self):
        with patch.object(validate_inventory.shutil, "which", return_value=None), \
                patch("builtins.open", side_effect=FileNotFoundError("missing inventory file")):
            buf = io.StringIO()
            with redirect_stdout(buf):
                with self.assertRaises(SystemExit) as raised:
                    validate_inventory.main([])
        self.assertEqual(raised.exception.code, 1)
        self.assertIn("No inventory variables could be loaded", buf.getvalue())

    def test_main_falls_back_to_file_parsing_when_ansible_inventory_binary_is_missing(self):
        fallback_content = (
            "---\n"
            "podman_cms_user: dsom-admin\n"
            "podman_cms_uid: 2001\n"
            "podman_cms_group: dsom-admin\n"
            "podman_cms_gid: 2001\n"
        )
        with patch.object(validate_inventory.shutil, "which", return_value=None), \
                patch("builtins.open", mock_open(read_data=fallback_content)):
            buf = io.StringIO()
            with redirect_stdout(buf):
                with self.assertRaises(SystemExit) as raised:
                    validate_inventory.main([])
        self.assertEqual(raised.exception.code, 0)
        output = buf.getvalue()
        self.assertIn("ansible-inventory executable not found", output)
        self.assertIn("Attempting fallback YAML parsing", output)
        self.assertIn("Sovereign Gate validation complete", output)

    def test_main_applies_extra_vars_override_and_detects_the_resulting_conflict(self):
        fake_result = MagicMock(returncode=0, stdout=self._ansible_success_payload(), stderr="")
        with patch.object(validate_inventory.shutil, "which", return_value="/usr/bin/ansible-inventory"), \
                patch.object(validate_inventory.subprocess, "run", return_value=fake_result):
            buf = io.StringIO()
            with redirect_stdout(buf):
                with self.assertRaises(SystemExit) as raised:
                    validate_inventory.main(["-e", "podman_cms_uid=9999"])
        self.assertEqual(raised.exception.code, 1)
        self.assertIn("podman_cms_uid", buf.getvalue())
        self.assertIn("9999", buf.getvalue())

    def test_main_uses_the_module_level_subprocess_run_when_ansible_binary_is_present(self):
        # Ensures the relocated top-level `subprocess` import is what main()
        # actually invokes (rather than any stale/local reference).
        fake_result = MagicMock(returncode=0, stdout=self._ansible_success_payload(), stderr="")
        with patch.object(validate_inventory.shutil, "which", return_value="/usr/bin/ansible-inventory"), \
                patch.object(validate_inventory.subprocess, "run", return_value=fake_result) as mock_run:
            buf = io.StringIO()
            with redirect_stdout(buf):
                with self.assertRaises(SystemExit):
                    validate_inventory.main([])
        mock_run.assert_called_once()
        called_cmd = mock_run.call_args[0][0]
        self.assertIn("ansible-inventory", called_cmd[0])

    def test_main_exits_one_when_one_required_key_is_missing(self):
        """Regression test ensuring SystemExit(1) is raised when exactly one required key is absent."""
        payload_vars = dict(validate_inventory.required)
        del payload_vars["podman_cms_user"] # Remove exactly one required identity key
        fake_result = MagicMock(returncode=0, stdout=json.dumps({"all": {"vars": payload_vars}, "_meta": {"hostvars": {}}}), stderr="")
        with patch.object(validate_inventory.shutil, "which", return_value="/usr/bin/ansible-inventory"), \
                patch.object(validate_inventory.subprocess, "run", return_value=fake_result):
            buf = io.StringIO()
            with redirect_stdout(buf):
                with self.assertRaises(SystemExit) as raised:
                    validate_inventory.main([])
        self.assertEqual(raised.exception.code, 1)
        self.assertIn("Identity Standard violation", buf.getvalue())
        self.assertIn("required key 'podman_cms_user' is absent", buf.getvalue())

    def test_main_exits_one_when_vars_contain_only_unrelated_keys(self):
        """Regression test ensuring SystemExit(1) is raised when loaded variables contain only unrelated keys."""
        payload_vars = {"some_completely_unrelated_key": "some_value"}
        fake_result = MagicMock(returncode=0, stdout=json.dumps({"all": {"vars": payload_vars}, "_meta": {"hostvars": {}}}), stderr="")
        with patch.object(validate_inventory.shutil, "which", return_value="/usr/bin/ansible-inventory"), \
                patch.object(validate_inventory.subprocess, "run", return_value=fake_result):
            buf = io.StringIO()
            with redirect_stdout(buf):
                with self.assertRaises(SystemExit) as raised:
                    validate_inventory.main([])
        self.assertEqual(raised.exception.code, 1)
        self.assertIn("Identity Standard violation", buf.getvalue())
        self.assertIn("required key 'podman_cms_user' is absent", buf.getvalue())


class ValidateInventoryIsSafePathTest(unittest.TestCase):
    """Tests the is_safe_path helper in validate-inventory.py."""

    def test_is_safe_path_with_valid_and_invalid_paths(self):
        self.assertTrue(validate_inventory.is_safe_path("inventory/hosts.prod.yml"))
        self.assertFalse(validate_inventory.is_safe_path("../escaping_file.yml"))

    def test_is_safe_path_symlink_safety(self):
        """Tests that is_safe_path correctly rejects symlinks escaping the current working directory."""
        import tempfile
        with tempfile.TemporaryDirectory() as tmp_dir:
            parent_dir = os.path.dirname(tmp_dir)
            with tempfile.NamedTemporaryFile(dir=parent_dir, delete=False) as outside_f:
                outside_file = outside_f.name
                outside_f.write(b"secret data")

            try:
                symlink_path = os.path.join(tmp_dir, "bad_link.txt")
                os.symlink(outside_file, symlink_path)

                # is_safe_path should return False because realpath points outside current working directory
                self.assertFalse(validate_inventory.is_safe_path(symlink_path))
            except (OSError, NotImplementedError, AttributeError):
                # Symlinks not supported/allowed in the test environment, skip gracefully
                pass
            finally:
                if os.path.exists(outside_file):
                    os.remove(outside_file)


if __name__ == "__main__":
    unittest.main()
