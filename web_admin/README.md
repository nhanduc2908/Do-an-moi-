# Security Admin Portal

## Overview
Web-based administration portal for Security Assessment Platform.

## Features
- User Management
- Role Management
- System Configuration
- Audit Logs
- Compliance Management
- Report Generation

## Requirements
- PHP >= 8.1
- MySQL >= 8.0
- Node.js >= 18

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve