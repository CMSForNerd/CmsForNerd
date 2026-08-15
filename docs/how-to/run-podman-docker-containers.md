---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Run CmsForNerd in Containers with Podman or Docker"
description: "Build and run CmsForNerd locally or in production using Docker or rootless Podman — utilizing Containerfile and Dockerfile coexistence."
resource: "file:///docs/how-to/run-podman-docker-containers.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [containers, podman, docker, containerfile, dockerfile]
---

# 🛠️ How-To: Run CmsForNerd in Containers with Podman or Docker

This guide details how to build, run, and orchestrate **CmsForNerd v4.3.0** using **Rootless Podman 5+** or **Docker**.

CmsForNerd ships with dual container specifications:
* **`Dockerfile`**: Standard OCI specification used for Docker engines, Render cloud deployments, and GitHub Actions pipelines.
* **`Containerfile`**: Rootless Podman specification optimized for enterprise Red Hat Enterprise Linux, AlmaLinux, and Fedora environments.

---

## 📦 Step 1: Build the Container Image

### Using Podman:
```bash
podman build -t cmsfornerd:v4.3.0 -f Containerfile .
```

### Using Docker:
```bash
docker build -t cmsfornerd:v4.3.0 -f Dockerfile .
```

---

## 🚀 Step 2: Run the Container Locally

Run the container rootlessly on port `8080`, mounting the flat-file content and data directories:

### With Podman (SELinux `:Z` support):
```bash
podman run -d \
  --name cmsfornerd \
  -p 8080:80 \
  -v $(pwd)/contents:/var/www/html/contents:Z \
  -v $(pwd)/data:/var/www/html/data:Z \
  cmsfornerd:v4.3.0
```

### With Docker:
```bash
docker run -d \
  --name cmsfornerd \
  -p 8080:80 \
  -v $(pwd)/contents:/var/www/html/contents \
  -v $(pwd)/data:/var/www/html/data \
  cmsfornerd:v4.3.0
```

---

## 🛠️ Step 3: Container Orchestration with `podman-compose` / `docker-compose`

The repository includes a production-ready compose configuration. To launch:

```bash
# With Podman:
podman-compose up -d

# With Docker:
docker compose up -d
```

Verify running instances:
```bash
podman ps
# OR
docker ps
```

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
