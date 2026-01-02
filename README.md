# Laravel SaaS Platform – Multi-Tenant Billing

A backend-focused Laravel SaaS platform implementing **multi-tenant authentication**, **subscription billing**, and **plan-based access control** using Stripe.

This project demonstrates a realistic SaaS architecture with API-first design, tenant isolation, and centralized billing logic suitable for production environments.

---

## Tech Stack

-   Laravel
-   Laravel Sanctum (API authentication)
-   Stripe Cashier (subscriptions & billing)
-   MySQL

---

## Features

-   API-first authentication
-   Multi-tenant architecture
-   Stripe subscription billing
-   Multiple plans (Free / Pro / VIP)
-   Subscription lifecycle (subscribe, upgrade, cancel)
-   Middleware-based access control
-   Centralized billing logic

---

## Requirements

-   PHP 8.2+
-   Composer
-   MySQL
-   Stripe account (test keys)

---

## Installation

```bash
git clone https://github.com/your-username/laravel-saas-platform.git
cd laravel-saas-platform
composer install
```

## Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### Update .env with your database and Stripe keys:

_Register on Stripe in the test environnement to get the test credentials_

```bash
DB_DATABASE=saas_db
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
```

### Database

```bash
php artisan migrate
```

### Run the Project

```bash
php artisan serve
```

### Notes

-   Stripe subscriptions use Stripe Price IDs
-   Billing logic is handled via Laravel Cashier
-   Plan upgrade behavior is documented for production environments
-   Focus is on backend architecture rather than UI

## License

This project is intended for portfolio and educational purposes.
