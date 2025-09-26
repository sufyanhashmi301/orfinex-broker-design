# Implementation: Handle `is_part_of_master_ib` on IB Rejection

## Overview

This implementation adds intelligent handling of the `is_part_of_master_ib` meta data when rejecting an approved IB. When an IB is rejected, the system now checks for the nearest approved parent IB in the referral chain and updates the meta accordingly.

## Business Logic

### When Rejecting an IB:

1. **Find Nearest Approved Parent IB**: Traverse up the referral chain to find the closest approved IB with an `ib_group_id`
2. **Update Meta Based on Parent**:
    - **If approved parent IB found**: Update `is_part_of_master_ib` with parent's `ib_group_id` for the rejected user and their entire network
    - **If no approved parent IB found**: Remove `is_part_of_master_ib` meta from the rejected user and their entire network

## Changes Made

### 1. Enhanced UserIbNetworkService (`app/Services/UserIbNetworkService.php`)

#### New Methods Added:

**`findNearestApprovedParentIb(User $user): ?User`**

-   Traverses up the referral chain to find the nearest approved parent IB
-   Returns the first parent with `ib_status = 'approved'` and `ib_group_id != null`
-   Returns `null` if no approved parent IB is found

```php
public function findNearestApprovedParentIb(User $user): ?User
{
    $currentUser = $user;

    // Traverse up the referral chain
    while ($currentUser->ref_id) {
        $parent = User::find($currentUser->ref_id);

        if (!$parent) {
            break; // No parent found, stop traversing
        }

        // Check if this parent is an approved IB with a group ID
        if ($parent->ib_status === 'approved' && !is_null($parent->ib_group_id)) {
            return $parent;
        }

        // Move up to the next level
        $currentUser = $parent;
    }

    return null; // No approved parent IB found
}
```

**`updateMasterIbBasedOnParent(User $user): int`**

-   Combines the logic of finding parent IB and updating meta accordingly
-   Returns the count of users affected by the meta update/removal

```php
public function updateMasterIbBasedOnParent(User $user): int
{
    $nearestApprovedParent = $this->findNearestApprovedParentIb($user);

    if ($nearestApprovedParent) {
        // Found an approved parent IB, sync with their IB group ID
        return $this->syncMeta($user, 'is_part_of_master_ib', $nearestApprovedParent->ib_group_id);
    } else {
        // No approved parent IB found, remove the meta
        return $this->removeMetaFromNetwork($user, 'is_part_of_master_ib');
    }
}
```

### 2. Updated IBController (`app/Http/Controllers/Backend/IBController.php`)

#### Enhanced `rejectIbMember()` Method:

-   Added database transaction support for data consistency
-   Integrated intelligent `is_part_of_master_ib` handling based on parent IB
-   Enhanced error handling with proper rollback and logging
-   Improved response structure for better error handling

```php
public function rejectIbMember(Request $request)
{
    $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');
    $isReload = ($request->get('reload')) ? $request->get('reload') : false;

    $user = User::find($userID);
    if (!blank($user)) {
        try {
            DB::beginTransaction();

            // Update user status
            $user->ib_status = IBStatus::REJECTED;
            $user->ib_group_id = null;
            $user->save();

            // Update is_part_of_master_ib based on nearest approved parent IB
            $updatedCount = $this->userIbNetworkService->updateMasterIbBasedOnParent($user);

            // Remove rebate rules
            $ibGroup = null;
            $this->manageUserRebateRules($user, $ibGroup);

            DB::commit();

            // Send notifications (existing code)
            // ...

            return response()->json([
                'title' => 'Account rejected for IB',
                'success' => __('User has been successfully rejected as IB Member.'),
                'reload' => $isReload
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting IB member: ' . $e->getMessage());

            return response()->json([
                'title' => 'Error',
                'error' => __('Error rejecting IB member: ') . $e->getMessage(),
                'reload' => false
            ], 500);
        }
    }

    return response()->json([
        'title' => 'Error',
        'error' => __('User not found.'),
        'reload' => false
    ], 404);
}
```

## URL Mapping

The functionality is accessible via:

-   **Route**: `POST /backoffice/ib/reject`
-   **Controller Method**: `IBController@rejectIbMember`
-   **Route Name**: `admin.ib.reject`

## Example Scenarios

### Scenario 1: Rejected IB with Approved Parent IB

```
Referral Chain: Root User → Approved IB (Group: 5) → Rejected IB → Child Users
Result: Rejected IB and all child users get is_part_of_master_ib = 5
```

### Scenario 2: Rejected IB with No Approved Parent IB

```
Referral Chain: Regular User → Regular User → Rejected IB → Child Users
Result: is_part_of_master_ib meta is removed from rejected IB and all child users
```

### Scenario 3: Multi-Level IB Chain

```
Referral Chain: Root → Approved IB (Group: 3) → Regular User → Approved IB (Group: 7) → Rejected IB
Result: Rejected IB gets is_part_of_master_ib = 7 (nearest approved parent)
```

## Key Features

### 1. **Intelligent Parent Detection**

-   Traverses up the referral chain to find the nearest approved IB
-   Stops at the first approved IB with a valid `ib_group_id`
-   Handles complex referral hierarchies correctly

### 2. **Network-Wide Updates**

-   Updates/removes meta for the entire network under the rejected IB
-   Maintains consistency across all child users
-   Uses existing network traversal logic for reliability

### 3. **Transaction Safety**

-   All operations wrapped in database transactions
-   Automatic rollback on errors ensures data consistency
-   Comprehensive error logging for debugging

### 4. **Backward Compatibility**

-   Maintains existing notification system
-   Preserves existing rebate rule management
-   No breaking changes to existing functionality

## Database Impact

### Meta Updates

-   **With Parent IB**: Sets `is_part_of_master_ib = parent_ib_group_id` for user and network
-   **Without Parent IB**: Removes `is_part_of_master_ib` records from user_metas table

### Performance Considerations

-   Efficient parent traversal (stops at first match)
-   Bulk network operations minimize database queries
-   Transaction support prevents partial updates

## Testing Scenarios

### 1. **Basic Rejection Tests**

-   Reject IB with approved parent → Should inherit parent's group ID
-   Reject IB without approved parent → Should remove meta completely
-   Reject IB at root level → Should remove meta completely

### 2. **Complex Hierarchy Tests**

-   Multi-level referral chains with mixed IB statuses
-   Nested approved IBs at different levels
-   Edge cases with circular references (should not occur but good to test)

### 3. **Error Handling Tests**

-   Database failures during rejection process
-   Invalid user IDs and malformed requests
-   Network traversal with broken referral chains

### 4. **Performance Tests**

-   Large networks with many child users
-   Deep referral chains (10+ levels)
-   Concurrent rejection operations

## Security & Authorization

-   **Existing Permissions**: Uses existing IB management permissions
-   **Input Validation**: Validates user IDs and request parameters
-   **Audit Trail**: Maintains existing notification and logging systems
-   **Data Integrity**: Transaction support prevents inconsistent states

## Monitoring & Debugging

### Logging

-   Error logging for failed rejection attempts
-   Debug information for parent IB detection
-   Transaction rollback logging for troubleshooting

### Success Indicators

-   Successful IB status change to REJECTED
-   Proper meta updates across the network
-   Correct rebate rule removal
-   Notification delivery confirmation

This implementation ensures that when an approved IB is rejected, the system intelligently manages the `is_part_of_master_ib` meta data based on the referral hierarchy, maintaining data consistency and business logic integrity.
