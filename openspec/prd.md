# PRD — SpecGuard AI (Hackathon Edition) v2 Final

> **Tagline:** *AI-Powered Specification Compliance Auditor for Modern Git Workflow.*
> 

---

# 1. Product Overview

## Product Name

**SpecGuard AI**

---

## Vision

Membantu tim pengembang memastikan bahwa implementasi kode benar-benar sesuai dengan spesifikasi bisnis melalui AI audit berbasis Git workflow dan AI-guided implementation prompting.

---

## Core Value

SpecGuard AI bertindak sebagai:

- AI Business Logic Auditor
- Specification Compliance Checker
- Real-time Visual Progress Tracker
- AI-Guided Developer Prompt Assistant

---

# 2. Problem Statement

## Existing Problem

Saat ini proses *Code Review* hanya fokus pada:

- syntax,
- style guide,
- bug teknis,
- performance dasar.

Namun hampir tidak ada sistem otomatis yang mampu memvalidasi:

```
"Apakah implementasi sudah sesuai dengan requirement bisnis?"
```

Akibatnya:

- validation sering terlewat,
- logging tidak dibuat,
- authentication tidak diterapkan,
- fitur parsial dianggap selesai,
- developer kehilangan alignment terhadap PRD.

---

## Impact

Masalah tersebut menyebabkan:

- QA manual memakan waktu,
- revisi berulang,
- technical debt meningkat,
- requirement drift antara PRD dan implementasi kode.

---

# 3. Proposed Solution

SpecGuard AI mengintegrasikan:

- Git Webhook,
- AI Audit Engine,
- OpenSpec,
- Visual Flow Tracking,
- dan AI Prompt Recommendation

ke dalam satu pipeline otomatis.

---

## Compliance Philosophy

SpecGuard AI tidak berfokus pada:

- code style recommendation,
- syntax optimization,
- refactoring suggestion.

SpecGuard AI berfokus pada:

```
"Apakah requirement bisnis benar-benar sudah diimplementasikan?"
```

---

## AI-Guided Development Workflow

Jika requirement belum terpenuhi, SpecGuard AI akan:

- mendeteksi requirement yang hilang,
- menghasilkan rekomendasi prompt implementasi,
- membantu developer melanjutkan development menggunakan AI IDE seperti Cursor atau Copilot.

---

# 4. Golden Demo Flow (Hackathon Scenario)

## Scenario Overview

Hackathon demo harus menunjukkan:

```
Specification
↓
Git Push
↓
AI Audit
↓
Compliance Detection
↓
AI Prompt Recommendation
↓
Visual Feedback
```

---

## Demo Flow

### Step 1 — Initial State

Dashboard menampilkan:

- diagram Mermaid abu-abu,
- status feature belum selesai,
- compliance score = 0%.

---

### Step 2 — Incomplete Push

Developer melakukan:

```
git push
```

Tetapi implementasi:

- belum memiliki logging,
- belum ada validation.

---

### Step 3 — AI Audit Triggered

GitHub webhook mengirim payload ke SpecGuard AI.

Queue memproses audit.

AI membaca:

- OpenSpec,
- code diff.

---

### Step 4 — Partial Result

Dashboard menampilkan:

```
{
  "score":70,
  "status":"partial"
}
```

Visual Mermaid:

- Auth → Hijau
- Validation → Merah
- Logging → Kuning

---

### Step 5 — AI Prompt Recommendation

SpecGuard AI menghasilkan rekomendasi prompt:

```
Add Laravel validation for email and password fields.
Implement Log::warning() for failed login attempts.
Return JSON response for validation failure.
```

Developer dapat:

- copy prompt,
- melanjutkan implementasi di AI IDE.

---

### Step 6 — Fixed Push

Developer menambahkan:

- validation,
- logging.

Lalu melakukan:

```
git push
```

---

### Step 7 — Final Result

AI audit ulang.

Dashboard:

```
{
  "score":100,
  "status":"complete"
}
```

Semua node Mermaid berubah hijau.

---

# 5. Core Features (MVP Scope)

## 5.1 GitHub Webhook Listener

### Description

Endpoint backend untuk menerima Push Event dari GitHub.

### Responsibilities

- menerima payload,
- validasi signature,
- ekstraksi commit diff,
- push job ke Redis Queue.

### Endpoint

```
/webhook/github
```

---

## 5.2 Queue Processing System

### Description

Sistem antrean asynchronous menggunakan Redis.

### Purpose

Mencegah bottleneck saat webhook burst.

### Flow

```
Webhook
↓
Redis Queue
↓
AI Worker
```

---

## 5.3 AI Audit Engine

### Description

Microservice FastAPI yang membandingkan:

- OpenSpec,
- code diff GitHub.

### Responsibilities

- generate AI prompt,
- call OpenAI API,
- parse JSON response,
- return compliance result,
- generate recommended implementation prompt.

### AI Model

Gunakan:

- OpenAI `gpt-4o-mini`

Karena:

- cepat,
- murah,
- cocok untuk hackathon.

---

## 5.4 AI Prompt Recommendation Engine

### Description

Sistem rekomendasi prompt berbasis hasil audit AI.

### Purpose

Membantu developer memperbaiki requirement yang belum terpenuhi menggunakan AI-assisted workflow.

### Example Output

```
Suggested Prompt:
Add request validation for login endpoint.
Implement Log::warning() for failed login attempts.
```

---

## 5.5 Dynamic Mermaid Visualizer

### Description

Visualisasi real-time status implementasi.

### Features

- render Mermaid.js,
- dynamic node coloring,
- compliance score,
- missing requirement display,
- recommended prompt panel.

---

### Color Rules

| Status | Color |
| --- | --- |
| Complete | Green |
| Partial | Yellow |
| Missing | Red |
| Empty | Gray |

---

# 6. OpenSpec Format

## Purpose

OpenSpec menjadi:

# single source of truth.

---

## Example OpenSpec

```
feature: Create Order

endpoint:
  method: POST
  path: /api/orders

requirements:
  - authentication
  - validation
  - logging

flow:
  - user request
  - auth validation
  - input validation
  - save transaction
  - activity logging
  - json response
```

---

# 7. AI Audit Logic

## AI Input

### OpenSpec

### Git Code Diff

---

## AI Responsibilities

AI hanya memvalidasi:

| Rule | Description |
| --- | --- |
| Auth | Middleware/authentication |
| Validation | Input validation |
| Logging | Activity logging |
| Response | JSON/API response |

---

## Side Effect Verification

SpecGuard AI mampu mendeteksi side-effect implementation yang sering terlupakan.

Contoh:

- activity logging,
- validation call,
- middleware usage.

Jika requirement side effect tidak ditemukan:

```
{
  "logging":false
}
```

---

## Deliberate Limitation (Batasan Masalah)

AI TIDAK:

- membaca full repository,
- melakukan deep AST analysis,
- menggunakan vector database,
- melakukan autonomous coding,
- memodifikasi repository secara otomatis.

Karena:

hackathon fokus pada end-to-end compliance workflow.

---

# 8. AI Contract Response (FINAL)

Ini adalah kontrak baku antara:

- Laravel Backend
- FastAPI AI Engine

---

## JSON Response Standard

```
{
  "score":85,
  "status":"partial",
  "missing_requirements": [
"Validation implementation missing",
"Error logging not detected"
  ],
  "recommended_prompt":"Add Laravel validation for email and password fields and implement Log::warning() for failed login attempts.",
  "node_status": {
    "auth":true,
    "validation":false,
    "logging":false
  }
}
```

---

# 9. Mermaid Visualization Logic

## Mermaid Base Diagram

```

```

---

## Dynamic Rendering

Backend membaca:

```
node_status
```

Lalu inject class Mermaid:

```
auth=true → green
validation=false → red
```

---

# 10. Technical Architecture

## System Architecture

```
GitHub
   ↓
Webhook API (Laravel)
   ↓
Redis Queue
   ↓
FastAPI AI Engine
   ↓
OpenAI API
   ↓
PostgreSQL
   ↓
Dashboard + Mermaid.js
   ↓
Recommended Prompt UI
```

---

# 11. Technology Stack

| Component | Technology | Purpose |
| --- | --- | --- |
| Backend API | Laravel (PHP 8.2+) | Webhook + Dashboard |
| Frontend UI | Blade + TailwindCSS + Vite | Dashboard & Asset Bundling |
| Queue | Redis | Async processing |
| Database | PostgreSQL | Audit logs |
| Testing | PHPUnit | Unit & Feature Testing |
| AI Service | FastAPI (Python) | AI orchestration |
| AI Provider | OpenAI | Compliance analysis |
| Visualization | Mermaid.js | Dynamic flowchart |

---

# 12. Infrastructure Architecture

## Runtime Environment

Containerized microservice architecture.

---

## Deployment Strategy

### Hackathon Runtime

Gunakan:

```
Docker Compose
```

---

## Future Scalability

Arsitektur disiapkan untuk:

- AI worker scaling,
- cloud-native deployment,
- future Kubernetes integration.

---

# 13. Database Schema

## projects

```
id
name
repo_url
spec_content
created_at
```

---

## audits

```
id
project_id
commit_hash
score
status
result_json
created_at
```

---

# 14. Scope Limitation (STRICT)

## Out of Scope

Fitur berikut DILARANG dibuat selama hackathon:

- Multi-tenant architecture
- User authentication kompleks
- Vector Database
- RAG pipeline
- Full repository analysis
- AST semantic parsing
- Realtime websocket infrastructure
- Complex UI animation
- Autonomous AI coding agent
- Repository auto-modification

---

# 15. Success Metrics

Hackathon dianggap berhasil jika:

| Metric | Target |
| --- | --- |
| GitHub webhook working | ✅ |
| AI audit returns JSON | ✅ |
| Mermaid dynamic update | ✅ |
| Compliance score visible | ✅ |
| Recommended prompt generated | ✅ |
| Demo end-to-end stable | ✅ |

---

# 16. Team Division Recommendation

| Role | Responsibility |
| --- | --- |
| Backend Engineer | Laravel webhook + dashboard |
| AI Engineer | FastAPI + OpenAI integration |
| Frontend Engineer | Mermaid visualization + prompt UI |
| DevOps Engineer | Docker deployment + infrastructure |

---

# 17. Development Conventions & Guidelines

## 17.1 Git Workflow & Version Control
- **Branching Strategy:** Menggunakan pendekatan Feature Branching (contoh: `feature/github-webhook`, `fix/mermaid-render`).
- **Commit Messages:** Harus mematuhi **Conventional Commits** (contoh: `feat: implement queue processing`, `fix: correct status parsing`).
- **Push Triggers:** Setiap eksekusi `git push` ke branch utama (atau branch yang dipantau) harus dikonfigurasi untuk memicu webhook otomatis ke backend SpecGuard AI.

## 17.2 Coding Standards
- **Backend (PHP/Laravel):** 
  - Mematuhi standar penulisan **PSR-12**.
  - Mengutamakan penggunaan fitur bawaan Laravel secara maksimal, seperti *Form Requests* untuk validasi, *Eloquent ORM*, dan *Queues* (jangan membuat wheel baru).
- **Frontend (Blade/Vite/Tailwind):** 
  - Menggunakan pendekatan *utility-first* dengan **TailwindCSS** langsung di dalam view Blade.
  - Minimalisasi custom CSS di `app.css`. Asset diproses dan di-bundle menggunakan **Vite**.
- **AI Service (Python/FastAPI):**
  - Mengikuti standar **PEP 8**.
  - Wajib menggunakan *Type Hints* (Pydantic models) untuk memvalidasi input/output API dengan ketat.

## 17.3 OpenSpec-First Approach
- **Design Before Code:** Setiap fitur atau endpoint baru **wajib** didefinisikan ke dalam format dokumen `openspec/*.yaml` terlebih dahulu sebelum menulis baris kode apapun.
- **Single Source of Truth:** Dokumentasi OpenSpec menjadi referensi absolut (kontrak). Jika terjadi perbedaan antara requirements di PRD dengan implementasi, OpenSpec adalah penentunya saat AI melakukan audit.