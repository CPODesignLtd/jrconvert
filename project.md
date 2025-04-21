# Product Requirements Document (PRD)

## Project Title  
**Migration of Legacy PHP Application to .NET MVC (Latest Version)**

---

## 1. Overview

The current web application is built on PHP with a MySQL backend and has reached end-of-life in terms of support and maintainability. This project aims to convert the entire application into a .NET MVC application using the latest stable version of .NET, while preserving all current functionality and user experience.

The goal is a one-to-one conversion with a local Dockerized development environment. The new application must use the existing SQL insert scripts to populate sample data and must be testable with unit tests.

---

## 2. Goals & Objectives

- Migrate all business logic and user interface elements from PHP to .NET MVC (latest version).
- Maintain exact feature and UI parity—no changes to the design, flow, or business logic.
- Set up a Docker-based development environment with:
  - A containerized .NET MVC application.
  - A containerized MySQL database.
- Reuse existing SQL insert scripts for populating initial data in the MySQL instance.
- Implement unit tests to ensure code reliability and maintainability.
- Provide documentation for setup, development, and testing.
- use local mysql database based on mysql #https://hub.docker.com/r/mysql/mysql-server/

---

## 3. Functional Requirements

### 3.1 Application Features

- All existing functionality must be migrated as-is.
- Database-driven authentication (replicate current PHP logic for login sessions and permissions).
- Any file uploads/downloads or form submissions must function identically.

### 3.2 Database

- MySQL backend (~4GB in size).
- Must support import of current SQL insert scripts for pre-populating data.
- All database structure, constraints, and queries should be preserved or equivalent.

### 3.3 Docker Environment

- Provide Docker support for:
  - .NET MVC application container.
  - MySQL database container.
- Must run locally with a `docker-compose` configuration.
- Clear setup instructions must be provided for developers.

### 3.4 Testing

- Unit tests required for key components, services, and business logic.
- No integration or UI testing required at this time.
- Use of a modern .NET-compatible testing framework (e.g., xUnit, NUnit).

---

## 4. Non-Functional Requirements

- **Performance:** Application should match or exceed performance benchmarks of the current PHP application when running locally.
- **Security:** Authentication flow and session handling must replicate current security model.
- **Maintainability:** Code should follow .NET best practices and use clean architecture patterns.
- **Scalability (optional):** While not required immediately, architecture should not block future expansion.
- **LLM context model to use: https://github.com/modelcontextprotocol/servers/tree/main/src/postgres** 
---

## 5. Technical Requirements

- **Frontend:** Same as current UI (no design changes).
- **Backend:** .NET MVC (Latest Stable Version, e.g., .NET 8).
- **Database:** MySQL (same version as current or compatible).
- **Dev Environment:** Docker with Docker Compose.
- **Testing Framework:** xUnit/NUnit (developer preference).
- **IDE:** Visual Studio / VS Code (developer choice).
- **Version Control:** Git (assumed).

---

## 6. Constraints

- Exact functionality and behavior must be preserved.
- UI must match the original application pixel-for-pixel unless unavoidable due to framework limitations.
- Project must run locally via Docker with no external service dependencies.
- No change to business rules or database schema unless absolutely required.

---

## 7. Deliverables

- Full .NET MVC source code with parity to PHP version.
- Dockerized environment with Dockerfile(s) and `docker-compose.yml`.
- Sample data imported via existing SQL insert scripts.
- Unit test coverage for business logic and core functionality.
- Documentation including:
  - Local setup instructions.
  - Codebase overview.
  - Test execution guide.

---

## 8. Out of Scope

- Timeline and project scheduling.
- Deployment to staging/production environments.
- UI modernization or redesign.
- Integration or end-to-end testing.
- CI/CD pipeline setup.


## 9. files to convert

# Functional Requirements for Converting PHP Files to .NET

This document lists all PHP files in the `jrv` folder and their corresponding functional requirements for conversion into .NET. Files containing logic are highlighted as individual tasks.

| File Name              | Description/Logic                                                                 |
|------------------------|-----------------------------------------------------------------------------------|
| `bratislava_1.php`     | Contains logic for dynamic content rendering, user input handling, and JavaScript integration. |
| `volani.php`           | Includes JavaScript logic for processing packet data and rendering dynamic content. |
| `bratislava.php`       | Static content or minimal logic.                                                  |
| `budejovice.php`       | Static content or minimal logic.                                                  |
| `chomutov.php`         | Static content or minimal logic.                                                  |
| `chomutovtest.php`     | Static content or minimal logic.                                                  |
| `decin_vzor.php`       | Static content or minimal logic.                                                  |
| `decin.php`            | Static content or minimal logic.                                                  |
| `decode.php`           | Static content or minimal logic.                                                  |
| `download.php`         | Static content or minimal logic.                                                  |
| `gmap.php`             | Static content or minimal logic.                                                  |
| `header.php`           | Static content or minimal logic.                                                  |
| `hradec.php`           | Static content or minimal logic.                                                  |
| `implementace.php`     | Static content or minimal logic.                                                  |
| `index.php`            | Static content or minimal logic.                                                  |
| `indexpackets.php`     | Static content or minimal logic.                                                  |
| `info.php`             | Static content or minimal logic.                                                  |
| `ivyhledavac.php`      | Static content or minimal logic.                                                  |
| `jihlava.php`          | Static content or minimal logic.                                                  |
| `jr.php`               | Static content or minimal logic.                                                  |
| `jrw50.php`            | Static content or minimal logic.                                                  |
| `jrw50include.php`     | Static content or minimal logic.                                                  |
| `kosice.php`           | Static content or minimal logic.                                                  |
| `liberec.php`          | Static content or minimal logic.                                                  |
| `mlazne.php`           | Static content or minimal logic.                                                  |
| `novy_vyhladavac.php`  | Static content or minimal logic.                                                  |
| `olomouc.php`          | Static content or minimal logic.                                                  |
| `opava_implement.php`  | Static content or minimal logic.                                                  |
| `opava.php`            | Static content or minimal logic.                                                  |
| `opavaJoomla.php`      | Static content or minimal logic.                                                  |
| `ostrava.php`          | Static content or minimal logic.                                                  |
| `pardubice.php`        | Static content or minimal logic.                                                  |
| `plzen_old.php`        | Static content or minimal logic.                                                  |
| `plzen.php`            | Static content or minimal logic.                                                  |
| `repons_zal.html`      | Static content or minimal logic.                                                  |
| `repons.html`          | Static content or minimal logic.                                                  |
| `saveloadstructure.php`| Static content or minimal logic.                                                  |
| `sbox.php`             | Static content or minimal logic.                                                  |
| `sboxdebug.php`        | Static content or minimal logic.                                                  |
| `sboxh.php`            | Static content or minimal logic.                                                  |
| `sboxzal.php`          | Static content or minimal logic.                                                  |
| `t.php`                | Static content or minimal logic.                                                  |
| `table.php`            | Static content or minimal logic.                                                  |
| `tabs.php`             | Static content or minimal logic.                                                  |
| `teplice.php`          | Static content or minimal logic.                                                  |
| `test.html`            | Static content or minimal logic.                                                  |
| `test98.php`           | Static content or minimal logic.                                                  |
| `test99.php`           | Static content or minimal logic.                                                  |
| `testinclude.php`      | Static content or minimal logic.                                                  |
| `testinput.php`        | Static content or minimal logic.                                                  |
| `testoutput.php`       | Static content or minimal logic.                                                  |
| `trebic.php`           | Static content or minimal logic.                                                  |
| `trnava.php`           | Static content or minimal logic.                                                  |
| `usti.php`             | Static content or minimal logic.                                                  |
| `zilina.php`           | Static content or minimal logic.                                                  |