# SIS SaaS Multi-Tenant Platform

A comprehensive, multi-tenant Software as a Service (SaaS) School Management System. This platform allows directors to create custom school portals mapped to unique subdomains, complete with dynamic theming, a customizable page builder, and robust academic management backends.

## Key Features

1. **Multi-Tenant Architecture**: 
   - Wildcard subdomain routing maps individual schools (e.g., `school1.sis.localhost`) directly from a central database without duplicating source code.
2. **Dynamic Page Builder & Theming**:
   - Schools can select from distinct UI templates (Academic, Blank, Minimalist, Vibrant).
   - Global predefined CSS themes and custom primary color injection allowing infinite branding combinations.
   - Database-persisted custom HTML overrides for unique page designs.
3. **Comprehensive Backend API**:
   - Role-based authentication and secure endpoints.
   - Assessment grading, tracking, and statistical reporting.
   - Bulk CSV import for mass student and teacher registration.

## Installation & Setup

### Local XAMPP Environment
1. Clone this repository into `c:\xampp\htdocs\school-management-system`.
2. Ensure you have mapped `sis.localhost` and wildcard `*.sis.localhost` to `127.0.0.1` in your Windows `hosts` file.
3. Create a MySQL database and import the schema from `sql/schema.sql`.
4. Run the platform by navigating to `http://sis.localhost`.

### Docker (Run Anywhere)
To ensure the application runs seamlessly on any computer, regardless of OS or installed software, we have provided a Docker configuration.

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop/).
2. Run `docker-compose up -d` in the root directory.
3. This spins up an Nginx/Apache container for PHP and a MySQL container, handling all dependencies automatically.

## Optimization Strategies

To ensure this site scales properly and runs lightning-fast on any server:

1. **Database Indexing**: Ensure `subdomain` column in the `schools` table is indexed, as it is queried on every page load.
2. **OpCode Caching**: Enable PHP OPcache in `php.ini` to compile PHP scripts into memory, drastically reducing load times.
3. **Content Delivery Network (CDN)**: Host static assets (CSS, JS, Fonts, Images) on a CDN like Cloudflare to reduce server bandwidth and improve global latency.
4. **Query Optimization**: Use Redis or Memcached to cache the results of the `school_site_content` lookup, so the database isn't hit repeatedly for the same subdomain data.

## Project Architecture
- `public/`: Entry point, routing logic (`index.php`), and static assets.
- `src/`: Core PHP backend (Controllers, Models, Services, Config).
- `templates/`: Dynamic frontend components injected with database configuration variables.
- `sql/`: Database schema definitions.
