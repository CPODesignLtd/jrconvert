# ✅ Best Practices for .NET MVC Conversion Project

This document defines coding standards, architecture guidelines, and operational practices to ensure quality and maintainability for the .NET MVC conversion project of the legacy PHP system.

## 📁 Project Organization

src/ 
├── Controllers/ # Handle HTTP requests (MVC) 
├── Models/ # Domain and ViewModels 
├── Views/ # Razor Views (UI rendering) 
├── Services/ # Business logic layer 
├── Data/ # DbContext, repositories, migrations 
├── Middleware/ # Optional custom request processing 
├── Utilities/ # Shared helpers and extensions 
├── DB/ # Database structure and seed data 
├── Tests/ # xUnit unit tests 
└── Docker/ # Docker and Compose setup


## 🔍 Key Conventions

- Keep controllers minimal; delegate logic to services.
- Separate `ViewModels` from domain models for rendering Razor Views.
- Use DTOs (Data Transfer Objects) for external API data or form submissions.
- Use feature folders for large or modular sections (e.g., `Auth`, `Users`).
- Place shared models, interfaces, and utilities in dedicated folders like `Common` or `Shared`.

## 🧱 Architecture Guidelines

- Controllers should be thin and only handle routing, validation, and delegation.
- Services contain core business logic and depend on repositories through dependency injection.
- Repositories encapsulate data access logic and interact with `DbContext`.
- Views are tightly bound to their respective `ViewModels` for clear separation.
- Avoid business logic inside Razor views or controllers.

## 🧪 Testing Guidelines

- Use **xUnit** for all unit testing.
- Create a `Tests/` directory mirroring the main structure (e.g., `Services`, `Controllers`).
- Each logic file must have a corresponding test file.
- Run `dotnet test` on build or before commit to validate logic.
- Mock database access and external dependencies using interfaces and test doubles.

Example structure:

Tests/ 
├── Services/ 
│ ├── AuthServiceTests.cs 
│ └── UserServiceTests.cs 
├── Controllers/ 
│ └── LoginControllerTests.cs


## 🔌 Dependency Injection

- All services and repositories must be injected via constructors.
- Register interfaces and implementations in `Startup.cs` or `Program.cs`.

Example:
```csharp
services.AddScoped<IAuthService, AuthService>();
services.AddScoped<IUserRepository, UserRepository>();


## Database Structure and Migrations
Use the DB/structure.sql file to define the schema based on the legacy PHP database.

Run this script to initialize the database inside the Docker container.

Use seed.sql to preload sample data for testing and development.

Use raw SQL or Entity Framework Core with MySQL provider depending on compatibility.

## Configuration and Environment Variables
Store the connection string and sensitive config using environment variables.

Use appsettings.Development.json for local configuration.

Use IConfiguration or strongly-typed options for accessing values.

{
  "ConnectionStrings": {
    "DefaultConnection": "server=localhost;port=3306;database=legacydb;user=root;password=root;"
  }
}

# Docker Best Practices
Use docker-compose.yml for full local setup.

Map the DB/structure.sql to the MySQL init folder for automatic setup.

Run tests automatically in the Dockerfile to catch errors early.

```
RUN dotnet test --no-build --verbosity normal
```

## 💡 Code Style Guidelines
Keep methods under 20–30 lines when possible.

Limit controller action size to ensure readability.

Group related methods in regions or partial classes (if applicable).

Use ILogger<T> for structured logging instead of Console.WriteLine.

Follow C# naming conventions: PascalCase for classes/methods, camelCase for variables.

## 🚫 What to Avoid
No business logic in controllers or views.

No hardcoded connection strings or secrets in code.

No logic duplication across services—use helper classes or utilities.

Avoid tightly-coupled code that prevents testing.