# 🚀 New Time Contact Manager

[![Technology][php-badge]][php-url]
[![Technology][mezzio-badge]][mezzio-url]
[![Technology][nginx-badge]][nginx-url]
[![Technology][vue-badge]][vue-url]
[![Technology][vite-badge]][vite-url]
[![Technology][docker-badge]][docker-url]

This project was developed as part of a **technical assessment for Kirey Group / New Time S.p.A.**, showcasing a modular
architecture based on **PHP with [Mezzio][mezzio-url] (PSR-7/15) and a [Vue][vue-url] 3 SPA frontend**.

The goal is to demonstrate clean code principles, containerized deployment, and adherence to agile delivery practices.

## 🧭 Project Context

- **End Client:** [New Time S.p.A.][newtime-url]
- **Technology Integrator:** [Kirey Group][kirey-url]
- **Scenario:** Development of a web prototype for user contact management, featuring full CRUD operations and
  integration between backend and frontend components.

**Objectives:**

- Demonstrate capability in requirements analysis and modeling
- Apply modern architecture using PHP ([PSR-7], [PSR-15], SOLID principles, etc.)
- Follow best practices in versioning, containerization, and documentation
- Adopt a clear agile (Scrum) methodology and incremental delivery approach

## 🏗️ Technology Stack

| Layer           | Technology              | Purpose                                     |
|-----------------|-------------------------|---------------------------------------------|
| Backend         | PHP 8.4 + Mezzio        | Modular RESTful API compliant with PSR-7/15 |
| Frontend        | Vue 3 + Vite            | Lightweight CRUD Single Page Application    |
| Persistence     | SQLite                  | Simple and portable data storage            |
| Infrastructure  | Docker + Docker Compose | Simplified local execution and deployment   |
| Version Control | GitHub                  | Commit history and project documentation    |
| Testing         | PHPUnit / Vitest        | Basic test coverage for main flows          |

## 🧭 Agile Board (SCRUM)

This project follows an **agile approach inspired by the Scrum framework**.
All epics, user stories, and progress tracking are available publicly on Jira:

🔗 [Follow project progress on Jira](https://jonatanreginato.atlassian.net/jira/software/projects/NT/boards/1)

## ⚙️ Development Environment Setup

### Requirements

- [Docker][docker-url] installed on your machine
- [Docker Compose v2][docker-compose-url] for service orchestration

### Project Architecture

The project is fully containerized using Docker Compose, providing isolated environments for:

- Backend (NGINX reverse proxy / PHP 8.4 FPM / SQLite database)
- Frontend (Vue 3 + Vite, served via NGINX)

All services are automatically orchestrated and connected within the same Docker network.

### Quick Start

To build and start all containers, run:

```bash
$ docker compose up --build
```

This command will:

- Build and start all containers (backend, frontend, and SQLite database)
- Generate a self-signed SSL certificate for HTTPS
- Mount local source code for live development

### Available Services

| Service          | Description                     | URL / Port                                                  |
|------------------|---------------------------------|-------------------------------------------------------------|
| Frontend (Vue 3) | User interface served via NGINX | http://localhost:5173                                       |
| Backend          | RESTful API (PHP-FPM + NGINX)   | HTTP: http://localhost:8080 / HTTPS: https://localhost:8081 |
| SQLite           | Local lightweight database      | Mounted at /docker/backend/sqlite/database                  |

## 📁 Directory Structure

```
newtime-contact-manager/
├── backend/              # PHP backend source code
├── frontend/             # Vue 3 + Vite SPA
├── docker/               # Dockerfiles and configurations
│   ├── backend/
│   └── frontend/
├── compose.yaml          # Docker Compose orchestration
└── README.md             # Project documentation
```

## Architecture Principles

See [Architecture Principles](ARCHITECTURE_PRINCIPLES.md) for more information.

## 🔗 References

- [Docker Documentation](https://docs.docker.com/get-docker)
- [Docker Compose v2 Documentation](https://docs.docker.com/compose/install/)
- [Mezzio Framework](https://docs.mezzio.dev)
- [Vue 3](https://vuejs.org/)
- [Vite](https://vitejs.dev)

## Postman Collection

A Postman collection is available for testing the API endpoints.
Link: [newtime-contact-manager.postman_collection.json](newtime-contact-manager.postman_collection.json)

[php-url]: https://www.php.net/

[php-badge]: https://img.shields.io/badge/php-8.3-grey?style=for-the-badge&logo=php&logoColor=white&logoSize=auto&label=&labelColor=blue&color=grey

[mezzio-url]: https://docs.mezzio.dev/

[mezzio-badge]: https://img.shields.io/badge/mezzio-v3-013755?style=for-the-badge&labelColor=009655

[nginx-url]: https://www.nginx.com/

[nginx-badge]: https://img.shields.io/badge/nginx-009639?style=for-the-badge&logo=nginx&logoColor=white

[docker-url]: https://www.docker.com/

[docker-badge]: https://img.shields.io/badge/Docker-blue?style=for-the-badge&logo=Docker&logoColor=white

[PSR-7]: https://www.php-fig.org/psr/psr-7/

[PSR-15]: https://www.php-fig.org/psr/psr-15/

[docker-compose-url]: https://docs.docker.com/compose/install/

[ARCHITECTURE_PRINCIPLES.md]: ARCHITECTURE_PRINCIPLES.md

[newtime-url]: https://www.newtimegroup.it/

[kirey-url]: https://www.kireygroup.com/

[vue-url]: https://vuejs.org/

[vue-badge]: https://img.shields.io/badge/vue-3-gray?style=for-the-badge&logo=vuedotjs&labelColor=42B883&logoColor=white

[vite-url]: https://vitejs.dev/

[vite-badge]: https://img.shields.io/badge/vite-5-gray?style=for-the-badge&logo=vite&labelColor=646CFF&logoColor=white
