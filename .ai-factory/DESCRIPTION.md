# Project: LimboQuest API

## Overview
LimboQuest is a quest room management system API built with Laravel 12. The system manages quest bookings, sessions, locations, and pricing rules. It provides separate API endpoints for administrators and clients, with role-based access control.

## Core Features
- **Quest Management**: Create and manage quest rooms with details (playtime, player limits, difficulty, pricing)
- **Booking System**: Handle quest bookings with status tracking, pricing snapshots, and payment management
- **Session Management**: Manage quest sessions with scheduling and availability
- **Location Management**: Manage physical locations where quests are hosted
- **Pricing Rules**: Dynamic pricing rules for quest sessions
- **User Authentication**: Role-based authentication using Laravel Sanctum and Fortify
- **Admin & Client APIs**: Versioned API (V1) with separate endpoints for admin and client access

## Tech Stack
- **Language:** PHP 8.2+
- **Framework:** Laravel 12
- **Database:** MySQL
- **ORM:** Eloquent (Laravel's built-in ORM)
- **Authentication:** Laravel Sanctum (API tokens) + Laravel Fortify
- **Testing:** Pest PHP
- **Debugging:** Laravel Telescope
- **Logging:** Laravel Pail
- **Code Quality:** Laravel Pint (PHP CS Fixer)

## Architecture Notes
- **API Versioning**: Routes are organized by version (V1) with separate admin and client namespaces
- **Request/Response Pattern**: Uses custom `ApiResponse` class from `finzor-dev/api` package
- **Role-Based Access**: Custom roles package (`finzor-dev/roles`) for permission management
- **Middleware**: Custom middleware for JSON responses and admin access control
- **Soft Deletes**: Used for quests and locations
- **Enum Types**: Booking status uses PHP enums (`BookingStatusEnum`)

## Domain Models
- **User**: System users with roles (admin/client)
- **Location**: Physical locations hosting quests
- **Quest**: Quest room definitions with metadata (difficulty, playtime, pricing)
- **QuestSession**: Scheduled sessions for specific quests
- **Booking**: Customer bookings for quest sessions
- **PricingRule**: Dynamic pricing rules for sessions

## API Structure
```
/api/v1/admin/*  - Admin endpoints (requires admin role)
/api/v1/client/* - Client endpoints (for quest participants)
```

## Non-Functional Requirements
- **Logging**: Configurable via `LOG_LEVEL` environment variable
- **Error Handling**: Structured JSON error responses via `ForceJsonResponse` middleware
- **Security**: 
  - Sanctum token-based authentication
  - Role-based authorization
  - Admin middleware protection
- **Testing**: Pest PHP for feature and unit tests
- **Code Style**: Laravel Pint for automatic code formatting

## Development Tools
- **Laravel Sail**: Docker-based development environment
- **Laravel Telescope**: Debug and monitor application
- **Laravel Pail**: Real-time log viewing
- **Laravel Tinker**: Interactive REPL

## Environment
- Default locale: Russian (`ru`)
- Fallback locale: English (`en`)
- App URL: `http://api.limbo.local`
- Database: MySQL (connection via `DB_*` env vars)
