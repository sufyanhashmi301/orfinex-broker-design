# Branch System Updates - Key Changes

## Overview

The branch system has been updated based on your requirements to provide a more flexible and simplified approach.

## 🔄 **Key Changes Made**

### 1. **Simplified Branch CRUD**

-   **Removed**: `country`, `currency`, `timezone`, `settings` fields
-   **Kept**: Only essential fields: `name`, `code`, `status`
-   **Benefit**: Cleaner, simpler branch management

#### Before:

```sql
CREATE TABLE branches (
    id, name, code, country, currency, timezone, status, settings, timestamps
);
```

#### After:

```sql
CREATE TABLE branches (
    id, name, code, status, timestamps
);
```

### 2. **Multi-Branch Access for Staff**

-   **Added**: `admin_branches` pivot table for many-to-many relationships
-   **Removed**: Direct `branch_id` column from `admins` table
-   **Benefit**: Staff can now access multiple branches (not just super-admin)

#### New Structure:

```sql
CREATE TABLE admin_branches (
    id, admin_id, branch_id, timestamps
    UNIQUE(admin_id, branch_id)
);
```

#### Staff Access Examples:

-   **UAE Staff**: Can access UAE branch only
-   **Regional Manager**: Can access both UAE and Pakistan branches
-   **Super Admin**: Can access all branches (automatic)

### 3. **User Branch Assignment via user_metas**

-   **Removed**: Direct `branch_id` column from `users` table
-   **Added**: Storage in existing `user_metas` table
-   **Benefit**: Flexible, doesn't modify core user table structure

#### Implementation:

```sql
-- User branch assignment stored as:
INSERT INTO user_metas (user_id, key, value)
VALUES (user_id, 'branch_id', 'branch_id_value');
```

#### Helper Functions:

```php
getUserBranchId($userId)           // Get user's branch ID
setUserBranchId($userId, $branchId) // Set user's branch ID
getCurrentUserBranchIds()          // Get admin's accessible branches (array)
```

## 🏗️ **Technical Architecture**

### Database Changes

```
✅ branches table (simplified)
✅ admin_branches table (new pivot)
✅ user_metas usage (existing table)
✅ branch_id columns (only for transactions, methods, schemas, ib_groups)
❌ No changes to users/admins tables
```

### Model Relationships

```php
// Admin Model
public function branches() // Many-to-many
public function hasAccessToBranch($branchId)
public function assignToBranches(array $branchIds)

// User Model
public function getBranchAttribute() // Via user_metas
public function setBranchAttribute($branchId) // Via user_metas
```

### Access Control Logic

```php
// Multi-branch support for staff
if (!$admin->hasRole('Super-Admin')) {
    $accessibleBranchIds = $admin->branches()->pluck('id')->toArray();
    // Filter data by accessible branches
}

// User filtering via user_metas
$query->whereHas('user_metas', function($q) use ($branchIds) {
    $q->where('key', 'branch_id')->whereIn('value', $branchIds);
});
```

## 🎯 **Usage Scenarios**

### Scenario 1: UAE Branch Staff

```php
// Staff assigned to UAE branch only
$uaeStaff->assignToBranches([1]); // UAE branch ID = 1

// Can see:
- Users assigned to UAE branch
- UAE branch deposits/withdrawals
- UAE branch account types
- UAE branch IB groups

// Cannot see:
- Pakistan branch data
- Users from other branches
```

### Scenario 2: Regional Manager

```php
// Manager assigned to multiple branches
$regionalManager->assignToBranches([1, 2]); // UAE + Pakistan

// Can see:
- Users from both UAE and Pakistan branches
- Combined statistics and reports
- All methods and account types for both branches

// Cannot see:
- Other branches (if any)
- Super admin functions
```

### Scenario 3: User Assignment

```php
// Assign user to UAE branch
setUserBranchId($user->id, 1); // UAE branch

// Assign user to Pakistan branch
setUserBranchId($user->id, 2); // Pakistan branch

// User with no branch assignment
// (can be managed by any staff with appropriate permissions)
```

## 🔧 **Configuration Examples**

### Enable Branch System

```php
// In admin settings
setting(['branch_system_enabled' => true], 'global');
```

### Create Branches

```php
// UAE Branch
Branch::create([
    'name' => 'UAE Branch',
    'code' => 'UAE',
    'status' => true
]);

// Pakistan Branch
Branch::create([
    'name' => 'Pakistan Branch',
    'code' => 'PAK',
    'status' => true
]);
```

### Assign Staff to Branches

```php
// UAE staff - single branch access
$uaeStaff->assignToBranches([1]); // UAE only

// Regional manager - multi-branch access
$regionalManager->assignToBranches([1, 2]); // UAE + Pakistan

// Super admin - automatic access to all branches (no assignment needed)
```

### Assign Users to Branches

```php
// UAE users
setUserBranchId($user1->id, 1);
setUserBranchId($user2->id, 1);

// Pakistan users
setUserBranchId($user3->id, 2);
setUserBranchId($user4->id, 2);

// Unassigned users (can be managed by any authorized staff)
// No user_metas entry with key='branch_id'
```

## 🚀 **Benefits of New Approach**

### 1. **Flexibility**

-   **Multi-branch staff access**: Not limited to single branch
-   **Granular permissions**: Assign specific branches to specific staff
-   **Scalable**: Easy to add new branches and staff assignments

### 2. **Simplicity**

-   **Clean branch model**: Only essential fields
-   **Existing table usage**: Leverages user_metas table
-   **No core table modifications**: Users/admins tables unchanged

### 3. **Backward Compatibility**

-   **Configurable system**: Can be disabled completely
-   **Graceful degradation**: Works with or without branch assignments
-   **Zero risk**: No impact when disabled

### 4. **Real-world Usage**

-   **UAE Branch**: Independent operations with dedicated staff
-   **Pakistan Branch**: Separate management with local staff
-   **Regional Oversight**: Managers can oversee multiple branches
-   **Central Control**: Super admin maintains full system access

## 📋 **Implementation Checklist**

### Phase 1: Database Setup

-   [ ] Create `branches` table (simplified)
-   [ ] Create `admin_branches` pivot table
-   [ ] Add `branch_id` to relevant tables (transactions, methods, etc.)
-   [ ] Create branch system setting

### Phase 2: Model Updates

-   [ ] Update Admin model with branches relationship
-   [ ] Update User model with user_metas branch support
-   [ ] Add branch support traits
-   [ ] Update helper functions

### Phase 3: Access Control

-   [ ] Update `getAccessibleUserIds()` function
-   [ ] Implement multi-branch filtering
-   [ ] Add branch-aware scopes
-   [ ] Test access control logic

### Phase 4: UI Updates

-   [ ] Branch management interface
-   [ ] Staff branch assignment interface
-   [ ] User branch assignment interface
-   [ ] Branch system enable/disable toggle

### Phase 5: Testing & Deployment

-   [ ] Test with branch system disabled (backward compatibility)
-   [ ] Test with branch system enabled (isolation)
-   [ ] Test multi-branch staff access
-   [ ] Performance testing and optimization

## 🎉 **Ready for Implementation**

The updated branch system is now ready for implementation with:

✅ **Simplified branch CRUD** (name, code, status only)  
✅ **Multi-branch staff access** (via admin_branches pivot)  
✅ **User branch assignment** (via user_metas table)  
✅ **Configurable system** (enable/disable functionality)  
✅ **Backward compatibility** (zero impact when disabled)

This approach provides the perfect balance of flexibility, simplicity, and functionality for your UAE and Pakistan branch operations.
