# Copilot Instructions for AI Agents

## Project Overview
- This is a Laravel-based real estate web application.
- The codebase follows standard Laravel conventions for MVC, routing, middleware, and service providers.
- Key directories:
  - `app/Http/Controllers/`: Application logic and request handling
  - `app/Models/`: Eloquent ORM models (e.g., `User.php`)
  - `resources/views/`: Blade templates for UI (organized by feature: `admin/`, `agent/`, etc.)
  - `routes/`: Route definitions (`web.php`, `auth.php`, etc.)
  - `database/`: Migrations, seeders, and factories
  - `public/`: Web root for assets and entry point (`index.php`)

## Developer Workflows
- **Development server:**
  - Start with `php artisan serve` (default: http://localhost:8000)
- **Build assets:**
  - Use Vite: `npm run dev` (for hot reload) or `npm run build` (for production)
- **Testing:**
  - Run tests with `vendor\bin\pest` or `vendor\bin\phpunit`
  - Feature and unit tests are in `tests/Feature/` and `tests/Unit/`
- **Database migrations/seeders:**
  - Run migrations: `php artisan migrate`
  - Seed database: `php artisan db:seed`

## Project-Specific Patterns
- **Blade Views:**
  - Views are grouped by user role/feature (e.g., `admin/`, `agent/`, `auth/`)
  - Use Blade components in `resources/views/components/` for reusable UI
- **Controllers:**
  - Follow RESTful conventions; group related logic by resource
- **Requests & Middleware:**
  - Custom request validation in `app/Http/Requests/`
  - Middleware in `app/Http/Middleware/`
- **Service Providers:**
  - Register custom services in `app/Providers/`

## Integration & Dependencies
- Uses Laravel's built-in authentication and Eloquent ORM
- Frontend assets managed with Vite, Tailwind CSS, and PostCSS
- External packages managed via Composer (`composer.json`) and npm (`package.json`)

## Conventions & Tips
- Use environment variables via `.env` for config
- Follow PSR-4 autoloading for PHP classes
- Prefer Eloquent relationships for data access
- Use factories and seeders for test data
- Place custom scripts in `scripts/` (e.g., `generate-index.js`)

## References
- See `README.md` for general Laravel info
- Example files: `app/Models/User.php`, `routes/web.php`, `resources/views/admin/admin_dashboard.blade.php`

---

For non-standard patterns or new conventions, update this file to keep AI agents productive.
