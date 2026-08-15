---
okf_version: 0.1
type: guide
title: "🛠️ Containerized Execution with Podman and Docker"
description: "Instructions for building and launching CmsForNerd using Containerfile or Dockerfile specifications."
resource: "file:///docs/how-to/run-podman-docker-containers.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [containers, podman, docker, containerfile, dockerfile]
---

# 🛠️ Containerized Execution with Podman and Docker

CmsForNerd provides dual container definitions (`Containerfile` for rootless Podman and `Dockerfile` for Docker/Render).

---

## 🐳 Quick Execution Commands

### Podman (Rootless with SELinux `:Z`):
```bash
podman build -t cmsfornerd-app:latest -f Containerfile .
podman run -d -p 8080:80 -v $(pwd)/contents:/var/www/html/contents:Z cmsfornerd-app:latest
```

### Docker Compose:
```bash
docker compose up -d --build
```

---

*CmsForNerd Container Execution Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
