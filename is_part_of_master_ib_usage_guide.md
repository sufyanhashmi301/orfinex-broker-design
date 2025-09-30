# Complete Usage Guide: `is_part_of_master_ib` in user_metas Table

This document provides a comprehensive overview of where and how `is_part_of_master_ib` is used throughout the Orfinex Broker application.

## Table Structure

The `user_metas` table stores key-value pairs for users:

-   `user_id` - Foreign key to users table
-   `meta_key` - The meta key (in this case: 'is_part_of_master_ib')
-   `meta_value` - The IB group ID that the user belongs to

## Files Where `is_part_of_master_ib` is Used

### 1. **app/Console/Commands/MarkPartOfMasterIBUsers.php**

**Purpose**: Console command to mark users and their network as part of Master IB
**Usage**:

```php
// Set the user's own is_part_of_master_ib value
$user->user_metas()->updateOrCreate(
    ['meta_key' => 'is_part_of_master_ib'],
    ['meta_value' => $user->ib_group_id]
);

// Sync the entire network
$count = $service->syncMeta($user, 'is_part_of_master_ib', $user->ib_group_id);
```

**Command**: `php artisan ib:mark-part-of-master-ib`

### 2. **app/Http/Controllers/Frontend/ForexSchemaController.php**

**Purpose**: Controls which Forex schemas are available to users based on their IB status
**Usage**:

```php
// Get user's master IB status
$isPartOfMasterIb = UserMeta::where('user_id', $user->id)
    ->where('meta_key', 'is_part_of_master_ib')
    ->value('meta_value');

// Logic for IB users vs non-IB users
if ($isPartOfMasterIb) {
    $ibGroup = IbGroup::with(['rebateRules.forexSchemas'])->find($isPartOfMasterIb);
    // Show schemas from IB group rebate rules
} else {
    // Show global and country/tag matching schemas
}
```

### 3. **app/Http/Controllers/Backend/UserController.php**

**Purpose**: Admin panel user management - shows available schemas and email filtering
**Usage**:

```php
// Line 532: Get user's IB status for schema selection
$isPartOfMasterIb = user_meta('is_part_of_master_ib', null, $user);

if ($isPartOfMasterIb) {
    $ibGroup = IbGroup::with('rebateRules.forexSchemas')->find($isPartOfMasterIb);
    // Add schemas from IB group
}

// Lines 1565-1577: Email filtering by IB groups
$query->whereHas('user_metas', function($q) {
    $q->where('meta_key', 'is_part_of_master_ib');
});
```

### 4. **app/Http/Controllers/Backend/IBController.php**

**Purpose**: IB approval process - syncs meta data when approving IBs
**Usage**:

```php
// Lines 540-544: When approving an IB
$this->userIbNetworkService->syncMeta(
    $user,
    'is_part_of_master_ib',
    $user->ib_group_id
);
```

### 5. **app/Http/Controllers/Backend/IBControllerSecure.php**

**Purpose**: Secure IB operations - syncs meta data
**Usage**:

```php
// Lines 78-82: Sync user metadata
$this->userIbNetworkService->syncMeta(
    $user,
    'is_part_of_master_ib',
    $user->ib_group_id
);
```

### 6. **app/Http/Controllers/Auth/RegisteredUserController.php**

**Purpose**: User registration - inherits IB status from referrer
**Usage**:

```php
// Lines 246-253: During referral handling
$isPartOfMasterIb = user_meta('is_part_of_master_ib', null, $referrer);

if ($isPartOfMasterIb) {
    $user->user_metas()->updateOrCreate(
        ['meta_key' => 'is_part_of_master_ib'],
        ['meta_value' => $isPartOfMasterIb]
    );
}
```

### 7. **app/Http/Controllers/Backend/ReferralController.php**

**Purpose**: Referral management - propagates IB status to child users
**Usage**:

```php
// Lines 116-123: When adding child users
$isPartOfMasterIB = user_meta('is_part_of_master_ib', null, $pUser);

if ($isPartOfMasterIB) {
    $childUser->user_metas()->updateOrCreate(
        ['meta_key' => 'is_part_of_master_ib'],
        ['meta_value' => $isPartOfMasterIB]
    );
}
```

## Key Methods to Fetch Records

### 1. Using Helper Function (Recommended)

```php
// For current user
$isPartOfMasterIb = user_meta('is_part_of_master_ib');

// For specific user
$isPartOfMasterIb = user_meta('is_part_of_master_ib', null, $user);
```

### 2. Direct Database Query

```php
// Single user
$isPartOfMasterIb = UserMeta::where('user_id', $userId)
    ->where('meta_key', 'is_part_of_master_ib')
    ->value('meta_value');

// Multiple users
$usersWithMasterIb = UserMeta::where('meta_key', 'is_part_of_master_ib')
    ->with('user')
    ->get();
```

### 3. Using Eloquent Relationships

```php
// Through User model
$user = User::with(['user_metas' => function($query) {
    $query->where('meta_key', 'is_part_of_master_ib');
}])->find($userId);

$isPartOfMasterIb = $user->user_metas->first()?->meta_value;
```

### 4. Filtering Users by IB Status

```php
// Users who are part of any master IB
$usersInMasterIb = User::whereHas('user_metas', function($query) {
    $query->where('meta_key', 'is_part_of_master_ib');
})->get();

// Users in specific IB groups
$usersInSpecificIb = User::whereHas('user_metas', function($query) use ($ibGroupIds) {
    $query->where('meta_key', 'is_part_of_master_ib')
          ->whereIn('meta_value', $ibGroupIds);
})->get();
```

## Business Logic Flow

1. **IB Approval**: When an IB is approved with `ib_group_id`, the system sets their `is_part_of_master_ib` meta
2. **Network Propagation**: The `UserIbNetworkService` propagates this meta to all users in the IB's referral network
3. **Registration Inheritance**: New users registered under an IB automatically inherit the `is_part_of_master_ib` status
4. **Schema Access Control**: Users' access to Forex schemas is determined by their IB status
5. **Email Filtering**: Admin can filter users by IB groups using this meta

## Related Services

### UserIbNetworkService

-   **File**: `app/Services/UserIbNetworkService.php`
-   **Method**: `syncMeta($user, $metaKey, $metaValue)`
-   **Purpose**: Propagates meta data to entire referral network

## Database Operations

### Create/Update

```php
$user->user_metas()->updateOrCreate(
    ['meta_key' => 'is_part_of_master_ib'],
    ['meta_value' => $ibGroupId]
);
```

### Read

```php
$value = UserMeta::where('user_id', $userId)
    ->where('meta_key', 'is_part_of_master_ib')
    ->value('meta_value');
```

### Bulk Operations

```php
// Get count per IB group
$counts = UserMeta::where('meta_key', 'is_part_of_master_ib')
    ->select('meta_value', DB::raw('COUNT(*) as count'))
    ->groupBy('meta_value')
    ->get();
```

## Security Considerations

1. Always validate IB group IDs before setting meta values
2. Ensure proper authorization before accessing IB-related data
3. Use transactions when updating multiple related records
4. Validate user permissions before showing IB-specific schemas

## Performance Tips

1. Index the `user_metas` table on `(user_id, meta_key)` for faster lookups
2. Use eager loading when fetching multiple users with their meta data
3. Consider caching frequently accessed IB group information
4. Use bulk operations for network-wide updates
