# SpecGuard AI — Deployment Guide

## Architecture

```
GitHub Webhook → Laravel API → Redis Queue → Laravel Worker → OpenAI API → PostgreSQL → Dashboard + Mermaid.js
```

| Service | Purpose | Image |
|---|---|---|
| **laravel** | Dashboard, webhook API, AI service layer | `dashboard_specguardai-laravel` |
| **laravel-worker** | Redis queue consumer for async audits | same image, `CONTAINER_ROLE=worker` |
| **postgres** | Persistent data store (projects, audits) | `postgres:16-alpine` |
| **redis** | Queue broker + cache | `redis:7-alpine` |

---

## Option 1: Docker Compose (Primary — Hackathon Demo)

### Prerequisites

- Docker Desktop with Docker Compose v2+

### Quick Start

```bash
# 1. Build and start all services
docker compose up -d --build

# 2. Check status
docker compose ps

# 3. Access dashboard
open http://localhost:8080
```

### Environment Configuration

Copy and edit `.env.docker` with your actual API keys:

```bash
# Required: set your AI API key
OPENAI_API_KEY=your-actual-key

# Required: set webhook secret
GITHUB_WEBHOOK_SECRET=your-actual-secret
```

Then restart:

```bash
docker compose restart laravel laravel-worker
```

### Service Ports

| Service | URL |
|---|---|
| Dashboard | http://localhost:8080 |
| PostgreSQL | localhost:5432 |
| Redis | localhost:6379 |

### Common Operations

```bash
# View logs
docker compose logs -f laravel
docker compose logs -f laravel-worker

# Rebuild after code changes
docker compose up -d --build

# Stop all services
docker compose down

# Stop and remove all data
docker compose down -v

# Access Laravel CLI
docker exec -it specguard-laravel php artisan tinker
```

---

## Option 2: Kubernetes Deployment

### Prerequisites

- A Kubernetes cluster (minikube, kind, Docker Desktop K8s, or cloud)
- `kubectl` configured and connected
- Docker images built locally

### Step 1: Build Docker Images

```bash
docker compose build
```

### Step 2: Load Images into Cluster

**Minikube:**
```bash
minikube image load dashboard_specguardai-laravel:latest
```

**Kind:**
```bash
kind load docker-image dashboard_specguardai-laravel:latest
```

**Docker Desktop K8s:** Images are automatically available.

### Step 3: Update Secrets

Edit `k8s/config.yaml` and set your actual API keys in the Secret section:

```yaml
stringData:
  APP_KEY: "base64:your-actual-app-key"
  DB_PASSWORD: "specguard_secret"
  OPENAI_API_KEY: "your-actual-api-key"
  GITHUB_WEBHOOK_SECRET: "your-actual-webhook-secret"
```

### Step 4: Deploy

```bash
# Apply all manifests in order
kubectl apply -f k8s/
```

### Step 5: Verify

```bash
# Check all pods
kubectl get pods -n specguard

# Check services
kubectl get svc -n specguard

# Check pod logs
kubectl logs -n specguard deployment/laravel
kubectl logs -n specguard deployment/laravel-worker

# Verify Redis connectivity
kubectl exec -n specguard deployment/laravel -- php artisan tinker --execute="echo Redis::ping();"
```

### Step 6: Access Dashboard

**Option A — Port Forward (simplest):**
```bash
kubectl port-forward -n specguard svc/laravel 8080:8080
# Then open http://localhost:8080
```

**Option B — Ingress (requires nginx ingress controller):**
```bash
# Enable ingress on minikube
minikube addons enable ingress

# Add to /etc/hosts (or C:\Windows\System32\drivers\etc\hosts)
# <minikube-ip>  specguard.local

# Then open http://specguard.local
```

---

## Scaling Strategy

### Docker Compose

Not applicable for horizontal scaling. Use Kubernetes for multi-replica workloads.

### Kubernetes

**Scale queue workers** (for faster audit processing):

```bash
kubectl scale deployment laravel-worker -n specguard --replicas=3
```

**Scale web pods** (for more dashboard capacity):

```bash
kubectl scale deployment laravel -n specguard --replicas=2
```

**Check scaling:**

```bash
kubectl get pods -n specguard -l app=laravel
```

> PostgreSQL and Redis remain single-replica. For hackathon purposes, this is sufficient. Production would require StatefulSets and Redis Sentinel.

---

## Manifest Reference

| File | Resources |
|---|---|
| `k8s/namespace.yaml` | Namespace `specguard` |
| `k8s/config.yaml` | ConfigMap + Secret (shared env vars) |
| `k8s/postgres.yaml` | PVC + Deployment + Service |
| `k8s/redis.yaml` | Deployment + Service |
| `k8s/laravel.yaml` | Deployment + Service (web) |
| `k8s/worker.yaml` | Deployment (queue worker) |
| `k8s/ingress.yaml` | Ingress (nginx, `specguard.local`) |

---

## Troubleshooting

### Docker

| Issue | Fix |
|---|---|
| Port 8080 in use | `docker compose down`, kill the other process, then `up` again |
| Migration errors | `docker exec specguard-laravel php artisan migrate:fresh --force` |
| Queue not processing | Check worker: `docker logs specguard-worker` |
| Build cache stale | `docker compose build --no-cache` |

### Kubernetes

| Issue | Fix |
|---|---|
| ImagePullBackOff | Ensure `imagePullPolicy: Never` and image is loaded into cluster |
| CrashLoopBackOff | Check logs: `kubectl logs -n specguard <pod-name>` |
| PVC pending | Check storage class: `kubectl get storageclass` |
| Ingress not working | Verify ingress controller: `kubectl get pods -n ingress-nginx` |
