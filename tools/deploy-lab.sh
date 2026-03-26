#!/usr/bin/env bash
# CmsForNerd Staging Deployment Wrapper
set -e

echo "[+] Executing local pre-flight checks (PHPStan Level 8 Zero-Debt)"
composer lab-check || { echo "[-] Pre-flight analysis failed. Aborting deployment."; exit 1; }

echo "[+] Deploying CMSForNerd to staging array..."
ansible-playbook deploy.yml -i inventory/hosts.staging.yml --ask-vault-pass

echo "[+] Deployment successful. Executing post-deploy semantic audit."
curl -s -o /dev/null -w "%{http_code}" http://staging.cmsfornerd.local/ | grep -q 200 || { echo "[-] Sanity check failed (Not 200 OK)"; exit 1; }
echo "[+] Target routing verified (200 OK)."
