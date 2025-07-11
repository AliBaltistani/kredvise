# User Withdraw Method Permissions

This feature allows administrators to control which withdrawal methods each user can access for withdrawals.

## Overview

The User Withdraw Method Permissions feature provides administrators with the ability to:

- Enable or disable specific withdrawal methods for individual users
- Manage these permissions through a dedicated admin interface
- Reset all withdrawal method permissions for a user

When a user attempts to withdraw funds, the system checks if they have permission to use the selected withdrawal method. If not, they will be unable to proceed with the withdrawal.

## Installation

To set up the User Withdraw Method Permissions feature, run the following artisan command:

```bash
php artisan setup:withdraw-method-permissions
```

This command will:
1. Create the `user_withdraw_method_permissions` table in your database
2. Add the necessary permissions to the admin panel
3. Populate initial data for all users (by default, all users will have access to all withdrawal methods)

## Usage

### Admin Panel

Administrators can manage user withdrawal method permissions through the admin panel:

1. Navigate to Users > User Detail
2. Click on the "Withdrawal Method Permissions" widget
3. Enable or disable specific withdrawal methods for the user
4. Click "Save Changes" to update the permissions
5. Alternatively, click "Reset" to remove all custom permissions for the user

### Withdrawal Process

During the withdrawal process, the system checks if the user has permission to use the selected withdrawal method:

1. When a user visits the withdrawal page, only withdrawal methods they have permission to use are displayed
2. If a user attempts to use a withdrawal method they don't have permission for, they will receive an error message

## Database Schema

The `user_withdraw_method_permissions` table has the following structure:

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | int | Foreign key to users table |
| withdraw_method_id | int | Foreign key to withdraw_methods table |
| status | boolean | 0: disabled, 1: enabled |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

## Artisan Commands

The following artisan commands are available for managing the User Withdraw Method Permissions feature:

| Command | Description |
|---------|-------------|
| `php artisan setup:withdraw-method-permissions` | Sets up the withdraw method permissions feature by creating the necessary table, adding permissions, and populating initial data |
| `php artisan populate:withdraw-method-permissions` | Populates the initial data for user withdraw method permissions |

## Models, Controllers, and Views

### Models

- `UserWithdrawMethodPermission` - Manages the relationship between users and withdrawal methods

### Controllers

- `UserWithdrawMethodPermissionController` - Handles admin actions for managing user withdrawal method permissions

### Views

- `admin/users/withdraw_method_permissions.blade.php` - Admin interface for managing user withdrawal method permissions

### Commands

- `SetupWithdrawMethodPermissions` - Command to set up the withdraw method permissions feature
- `PopulateUserWithdrawMethodPermissions` - Command to populate initial data for user withdraw method permissions

## Integration with Existing Code

The feature integrates with the existing withdrawal system by:

1. Adding a relationship between the `User` model and the `UserWithdrawMethodPermission` model
2. Modifying the `WithdrawController` to check for withdrawal method permissions before allowing a withdrawal
3. Adding a widget to the user detail page in the admin panel for easy access to the permissions management interface