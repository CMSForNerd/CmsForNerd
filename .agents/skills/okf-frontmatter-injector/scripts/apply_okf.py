import os
import re
import sys
import argparse
from datetime import datetime, timezone

def get_okf_type(filepath):
    path_parts = filepath.replace('\\', '/').split('/')
    if 'docs' in path_parts and 'governance' in path_parts:
        return 'governance_protocol'
    elif '.agents' in path_parts and 'skills' in path_parts:
        return 'agent_skill'
    elif '.agents' in path_parts and 'brain' in path_parts:
        return 'architecture_concept'
    elif 'tools-and-automation' in path_parts or 'tools' in path_parts:
        return 'automation_tool'
    elif 'playbooks' in path_parts or 'roles' in path_parts:
        return 'infrastructure_playbook'
    return 'documentation'

def extract_title(content, filename):
    match = re.search(r'^#\s+(.+)$', content, re.MULTILINE)
    if match:
        return match.group(1).strip()
    return os.path.splitext(filename)[0]

def extract_topics(filepath, content, okf_type):
    # Common words to filter out
    """
    Build topic tags from a file path, document headings, and resource type.
    
    Parameters:
    	filepath (str): Path used to derive topic words.
    	content (str): Markdown content whose headings supply additional topic words.
    	okf_type (str): Resource classification used to select fallback topics.
    
    Returns:
    	list[str]: Three to five topic tags.
    """
    stop_words = {
        'and', 'the', 'of', 'to', 'a', 'in', 'for', 'on', 'with', 'guide',
        'manual', 'how', 'howto', 'setup', 'an', 'is', 'it', 'by', 'at',
        'from', 'this', 'that', 'into', 'file', 'files', 'md', 'readme', 'agents',
        'agent', 'core', 'rulebook'
    }

    # 1. Start with words from filepath
    path_words = re.findall(r'[a-zA-Z]+', filepath.lower())

    # 2. Add words from the first few headings
    heading_words = []
    for line in content.split('\n')[:50]:
        if line.startswith('#'):
            heading_words.extend(re.findall(r'[a-zA-Z]+', line.lower()))

    # Combine and clean
    all_candidates = []
    for word in path_words + heading_words:
        if len(word) > 2 and word not in stop_words:
            if word not in all_candidates:
                all_candidates.append(word)

    # If not enough, add type-specific defaults
    defaults_by_type = {
        'governance_protocol': ['governance', 'dsom', 'policy', 'protocol', 'compliance'],
        'agent_skill': ['skill', 'agent', 'automation', 'tools', 'dsom'],
        'architecture_concept': ['architecture', 'brain', 'dsom', 'memory', 'concept'],
        'automation_tool': ['tool', 'automation', 'scripts', 'utility'],
        'infrastructure_playbook': ['ansible', 'playbook', 'infrastructure', 'deployment', 'podman'],
        'documentation': ['cms', 'nerd', 'documentation', 'guide']
    }

    type_defaults = defaults_by_type.get(okf_type, ['documentation', 'reference'])
    for d in type_defaults:
        if len(all_candidates) >= 5:
            break
        if d not in all_candidates:
            all_candidates.append(d)

    # Clamp to 3 to 5
    topics = all_candidates[:5]
    if len(topics) < 3:
        for d in type_defaults:
            if d not in topics:
                topics.append(d)
            if len(topics) >= 3:
                break

    return topics[:5]

def is_safe_path(filepath, base_dir=""):
    """
    Determine whether a path remains within a specified base directory.
    
    Parameters:
        filepath (str): Path to validate.
        base_dir (str): Directory that the path must remain within; defaults to the current working directory.
    
    Returns:
        bool: `True` if the path is within the base directory, `False` otherwise.
    """
    if not base_dir:
        base_dir = os.getcwd()
    abs_filepath = os.path.realpath(os.path.abspath(filepath))
    abs_base = os.path.realpath(os.path.abspath(base_dir))
    return os.path.commonpath([abs_base, abs_filepath]) == abs_base

def apply_okf(root_dir):
    """
    Apply OKF frontmatter metadata to Markdown files under a directory.
    
    Parameters:
        root_dir (str): Root directory to scan recursively.
    
    The scan excludes configured dependency, generated-content, and data directories. Unsafe root paths cause the process to exit with status 1.
    """
    if not is_safe_path(root_dir):
        print(f"Error: Path traversal blocked on root directory '{root_dir}'.", file=sys.stderr)
        sys.exit(1)

    timestamp = datetime.now(timezone.utc).strftime('%Y-%m-%dT%H:%M:%SZ')
    modified_count = 0
    total_count = 0

    for dirpath, dirnames, filenames in os.walk(root_dir):
        # Exclude directories in-place for walk optimization
        dirnames[:] = [d for d in dirnames if d not in ['.git', 'node_modules', 'scratch', 'vendor', 'data', 'asimp']]

        for filename in filenames:
            if not filename.endswith('.md'):
                continue

            filepath = os.path.join(dirpath, filename)
            rel_path = os.path.relpath(filepath, root_dir).replace('\\', '/')

            # Double check ignored paths in components
            path_parts = rel_path.split('/')
            ignored_dirs = {'.git', 'node_modules', 'scratch', 'vendor', 'data', 'asimp'}
            if any(part in ignored_dirs for part in path_parts):
                continue

            total_count += 1

            with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()

            # Handle UTF-8 BOM
            content = content.lstrip('\ufeff')

            # Identify parameters
            okf_type = get_okf_type(rel_path)
            title = extract_title(content, filename)
            topics = extract_topics(rel_path, content, okf_type)
            topics_str = "[" + ", ".join(topics) + "]"

            # Check if has frontmatter
            fm_match = re.match(r'^---\s*\n(.*?)\n---\s*\n', content, re.DOTALL)

            if not fm_match:
                # No frontmatter, create a new one
                frontmatter = f"""---
okf_version: 0.1
type: {okf_type}
title: "{title.replace('"', '')}"
description: "OKF-compliant documentation for {filename}."
resource: "file:///{rel_path}"
timestamp: {timestamp}
topics: {topics_str}
---
"""
                new_content = frontmatter + content
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Applied OKF to: {rel_path} (new frontmatter)")
                modified_count += 1
                continue

            # Frontmatter exists, check for missing fields
            fm_content = fm_match.group(1)
            body_content = content[fm_match.end():]
            fm_lines = fm_content.split('\n')

            # Parse existing keys
            existing_keys = set()
            for line in fm_lines:
                m = re.match(r'^([a-zA-Z0-9_\-]+)\s*:', line.strip())
                if m:
                    existing_keys.add(m.group(1))

            missing_fields = []
            if 'okf_version' not in existing_keys:
                missing_fields.append(f"okf_version: 0.1")
            if 'type' not in existing_keys:
                missing_fields.append(f"type: {okf_type}")
            if 'title' not in existing_keys:
                missing_fields.append(f'title: "{title.replace("\"", "")}"')
            if 'timestamp' not in existing_keys:
                # Use current timestamp
                missing_fields.append(f"timestamp: {timestamp}")
            if 'topics' not in existing_keys:
                missing_fields.append(f"topics: {topics_str}")

            if not missing_fields:
                continue

            # Append missing fields to frontmatter lines
            new_fm_lines = list(fm_lines)
            while new_fm_lines and new_fm_lines[-1].strip() == '':
                new_fm_lines.pop()
            new_fm_lines.extend(missing_fields)
            new_fm_content = "\n".join(new_fm_lines)

            new_content = f"---\n{new_fm_content}\n---\n" + body_content
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(new_content)

            print(f"Updated missing fields in: {rel_path} ({', '.join([f.split(':')[0] for f in missing_fields])})")
            modified_count += 1

    print(f"\nTotal Markdown files processed: {total_count}")
    print(f"Total Markdown files modified: {modified_count}")

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Inject/Ensure OKF frontmatter into Markdown files.")
    parser.add_argument("directory", help="The root directory to scan.")
    args = parser.parse_args()
    apply_okf(args.directory)
