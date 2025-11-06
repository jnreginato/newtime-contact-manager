# 📐 Architectural Standards and Design Principles

This project adopts a set of industry standards (PHP-FIG PSRs) combined with proven software architecture and design
principles.
The following sections describe the main conventions and patterns used throughout the project.

## 🧩 PSRs (PHP Standards Recommendations)

| PSR    | Nome                         | Description                                                             |
|--------|------------------------------|-------------------------------------------------------------------------|
| PSR-1  | Basic Coding Standard        | Defines fundamental coding conventions to ensure interoperability.      |
| PSR-3  | Logger Interface             | Common interface for loggers (LoggerInterface).                         |
| PSR-4  | Autoloading Standard         | Namespace-based autoloading via composer.json.                          | 
| PSR-6  | Caching Interface            | Interface for cache systems (CacheItemPoolInterface).                   |
| PSR-7  | HTTP Message Interface       | Defines interfaces for HTTP messages (Request, Response, etc.).         |
| PSR-11 | Container Interface          | Interface for dependency injection containers.                          |
| PSR-12 | Extended Coding Style        | Extends PSR-1 with detailed coding style conventions.                   | 
| PSR-13 | Link Definition Interface    | Standardized way to describe links (used in HATEOAS).                   |
| PSR-14 | Event Dispatcher             | Interfaces for event publishing and listening.                          | 
| PSR-15 | HTTP Server Request Handlers | Interfaces for middleware and request handlers.                         |
| PSR-16 | Simple Cache                 | Simplified caching interface (get, set, delete, etc.).                  |
| PSR-17 | HTTP Factory Interfaces      | Interfaces for creating HTTP message objects (Request, Response, etc.). |
| PSR-18 | HTTP Client                  | Interface for standardized HTTP client implementations.                 |
| PSR-20 | Clock Interface              | Abstraction for time access, useful for deterministic testing.          |

## 🧼 Clean Code

Based on Robert C. Martin (Uncle Bob)’s principles, emphasizing:

- Readability
- Simplicity
- Meaningful names
- Separation of responsibilities
- Useful and accurate comments

> ✨Write code that looks like it was written with care.

## 🧱 Hexagonal Architecture

Also known as Ports and Adapters, this approach promotes:

- Isolation of business logic from external concerns (frameworks, databases, etc.);
- Testability through decoupled core logic;
- Use of interfaces (ports) and implementations (adapters) for external interactions;
- Flexibility to replace external components (DB, APIs, etc.) without affecting the core domain.

> 📌 The core of the application should depend on nothing external.

## 🧠 Domain-Driven Design (DDD)

Focuses on modeling the real-world domain through:

- **Ubiquitous Language** shared between technical and business stakeholders;
- **Entities**, **Value Objects** and **Aggregates**;
- **Bounded Contexts** o define clear responsibility areas;
- **Separation of layers**: Domain, Application, Infrastructure, and Presentation.

> 🎯 Ideal for complex business domains with robust rules and logic.

## 📏 SOLID Principles

A set of five object-oriented design principles for maintainable software:

| Letter | Principle                       | Definition                                                                  | 
|--------|---------------------------------|-----------------------------------------------------------------------------|
| S      | Single Responsibility Principle | A class should have only one reason to change.                              |
| O      | Open/Closed Principle           | Open for extension, closed for modification.                                |
| L      | Liskov Substitution Principle   | Subtypes must be substitutable for their base types without breaking logic. |
| I      | Interface Segregation Principle | Prefer several small, specific interfaces over large, general ones.         |
| D      | Dependency Inversion Principle  | Depend on abstractions, not concrete implementations.                       |

> ✅ These principles make the code more modular, reusable, and testable.

## 🎨 Design Patterns

Widely used design patterns to solve common architectural problems:

- **Factory Method:** Creates instances without tightly coupling to concrete types;
- **Strategy:** Allows interchangeable algorithms at runtime;
- **Adapter:** Adapts one interface to another expected by the client;
- **Decorator:** Adds behavior dynamically without modifying existing code;
- **Service Locator and Dependency Injection:** Manages and resolves dependencies;
- **DTOs e Value Objects:** Transfers data with integrity and immutability;
- **Event Dispatcher:** Decouples event emitters from listeners;
- **Middleware Pipeline:** Enables sequential request/response processing;

> 🛠️ Patterns are used only when necessary, prioritizing readability and maintainability.

## Complementary Principles

### ✔️ KISS (Keep It Simple, Stupid)

Avoid unnecessary complexity. Simpler code is easier to understand, test, and maintain.

### ⏩️ YAGNI (You Aren’t Gonna Need It)

Implement only what is needed. Don’t build features based on future assumptions.

### 🔄 DRY (Don’t Repeat Yourself)

Avoid duplicating logic or knowledge. Centralize reusable behavior.

### 📦 CQRS (Command Query Responsibility Segregation)

Clear separation between read operations (queries) and write operations (commands), improving clarity and scalability.

### ⏱️ Event Sourcing

Stores the state of the system as a sequence of events, allowing reconstruction of the current state and facilitating
auditability and debugging.

### 🧩 Event-Driven Architecture (EDA)

An architecture centered around events, ideal for reactive and decoupled systems.
Closely related to PSR-14.

### 📥 Dependency Injection (DI)

Used extensively with PSR-11 (Container Interface), promoting low coupling and improved testability.

### 🔁 Inversion of Control (IoC)

Part of the “D” in SOLID - depend on abstractions rather than concrete implementations.

### 🏷 Value Objects

A DDD concept for representing immutable data that has intrinsic meaning and validation.

### 🏷 DTOs (Data Transfer Objects)

Objects that carry data between processes without containing business logic.

### 📦 Service Layer & Application Layer

Separation between application logic (use case orchestration) and domain logic (pure business rules).

### 🚪 Ports and Adapters (Onion/Clean Architecture)

A complement to Hexagonal Architecture, ensuring the domain remains isolated from external frameworks and services.

### 🪃 Middleware

Intermediate layers that process HTTP requests and responses, allowing chained operations like authentication, logging,
or caching.

✅ This project follows these principles to ensure clarity, extensibility, and maintainability throughout its evolution.
