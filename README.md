# Lopango Backend

> Complete financial and accounting management system designed for landlords to streamline rental tracking, leases, and payments.

[![Symfony](https://img.shields.io/badge/Symfony-8.x-black?logo=symfony)](https://symfony.com)
[![API Platform](https://img.shields.io/badge/API%20Platform-4.x-blue?logo=api)](https://api-platform.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?logo=php)](https://php.net)
[![Tests](https://img.shields.io/badge/tests-PHPUnit-green?logo=phpunit)](https://phpunit.de)
[![Architecture](https://img.shields.io/badge/architecture-hexagonal-purple)](#)
[![License](https://img.shields.io/badge/license-MIT-yellow)](LICENSE)

---

## Architectural Design

The core system is built on **Hexagonal Architecture** (Ports and Adapters) principles and strictly organized by domain-driven **Bounded Contexts** to decouple business capabilities from delivery mechanisms and infrastructure.

```

src/
├── SharedContext/
│ ├── Domain/ # Shared abstractions, value objects, domain exceptions
│ ├── Application/ # Cross-context ports, application-wide interfaces
│ ├── Infrastructure/ # Shared adapters, cross-cutting concerns, base persistence
│ └── Presentation/ # Global middlewares, shared API resources
│
└── IdentityAndAccess/ # Example of a Bounded Context
├── Domain/ # Pure business logic (aggregates, entities, domain repository interfaces)
├── Application/ # Use cases, Command/Query handlers (CQRS), DTOs
├── Infrastructure/ # Concrete adapters (Doctrine repositories, external APIs, framework services)
└── Presentation/ # Delivery layer (API Platform resources, custom state processors)

```

Each bounded context (IdentityAndAccess, RentalManagement, Accounting) encapsulates its lifecycle autonomously, communicating through strictly defined domain contracts or asynchronous events.

---

## Core Requirements

- **Runtime:** PHP 8.4 or higher
- **Package Manager:** Composer 2.x
- **Database Engine:** PostgreSQL or MySQL
- **Local Tooling:** Symfony CLI, Docker & Docker Compose

---

## Quick Start

Follow these steps to set up the development environment locally.

```bash
# Clone the repository
git clone [https://github.com/laurentmwana/lopango-backend.git](https://github.com/laurentmwana/lopango-backend.git)
cd lopango-backend

# Install dependencies
composer install

# Set up local environment variables
cp .env .env.local

# Run database setup and migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Populate the database with development fixtures (optional)
php bin/console doctrine:fixtures:load --no-interaction

# Boot the local development server
symfony serve

```

---

## Docker Environment

A pre-configured containerized stack is available for standard operations:

```bash
# Spin up infrastructure containers
docker-compose up -d

# Execute composer within the app container
docker compose exec app composer install

```

---

## Capabilities and Features

- **Owner Accounting Engines:** Fine-grained balance tracking, automated ledger balancing, and income-expense categorization.
- **Lease and Agreement Automation:** Dynamic contract validation, lifecycle tracking, and historical rent tracking.
- **Secured Identity and Access:** Dual-factor validation flow, robust stateful token emission, and secure refresh-token rotations.
- **Hypermedia Api Design:** Native OpenAPI/Swagger specification generation via API Platform, featuring clean data pagination, sorting, and filtering adapters.

---

## Testing Framework

Quality assurance is enforced using PHPUnit with isolated environments for distinct validation levels.

| Test Suite      | Focus Range                       | Target                                           |
| --------------- | --------------------------------- | ------------------------------------------------ |
| **Unit**        | Pure Domain / Value Objects       | Isolated business rules verification             |
| **Integration** | Infrastructure / Database Mapping | Adapter compatibility and persistence validation |
| **Functional**  | Full HTTP API Request / Response  | E2E API Platform endpoint validation             |

```bash
# Run the complete verification pipeline
vendor/bin/phpunit

# Isolate execution to a specific testing layer
vendor/bin/phpunit tests/Unit
vendor/bin/phpunit tests/Integration
vendor/bin/phpunit tests/Functional

```

---

## License

This software is released under the terms of the [MIT License](https://www.google.com/search?q=LICENSE).
