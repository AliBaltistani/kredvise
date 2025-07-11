# User Currency Permissions

This feature allows administrators to control which gateway currencies each user can access for deposits.

## Overview

The User Currency Permissions feature provides the following capabilities:

- Administrators can enable/disable specific gateway currencies for individual users
- By default, all users have access to all active gateway currencies
- The system prevents users from depositing using currencies they don't have permission to use
- The deposit form only shows currencies that the user has permission to use

## Installation

To set up the currency permissions feature, run the following command:

```bash
php artisan setup:currency-permissions
```

This command will:

1. Run the migration to create the `user_currency_permissions` table
2. Add the necessary permissions to the system
3. Populate initial currency permissions for existing users

## Usage

### Admin Panel

1. Navigate to the user detail page
2. Click on the "Currency Permissions" widget
3. Enable or disable specific currencies for the user
4. Click "Update" to save changes
5. Use the "Reset" button to reset all permissions to the default state (all enabled)

### How It Works

- When a user attempts to deposit, the system checks if they have permission to use the selected currency
- If the user doesn't have permission, they will see an error message
- The deposit form only displays currencies that the user has permission to use

## Database Schema

The feature adds a new table `user_currency_permissions` with the following structure:

- `id` - Primary key
- `user_id` - Foreign key to the users table
- `gateway_currency_id` - Foreign key to the gateway_currencies table
- `status` - Boolean indicating if the user has permission to use this currency
- `created_at` - Timestamp of creation
- `updated_at` - Timestamp of last update

## Commands

The following artisan commands are available:

- `setup:currency-permissions` - Run all setup steps
- `permissions:add-currency` - Add the necessary permissions to the system
- `permissions:populate-currency` - Populate initial currency permissions for existing users

## Models

- `UserCurrencyPermission` - Manages the relationship between users and gateway currencies

## Controllers

- `UserCurrencyPermissionController` - Handles admin actions for managing user currency permissions

## Views

- `admin/users/currency_permissions.blade.php` - Admin interface for managing user currency permissions