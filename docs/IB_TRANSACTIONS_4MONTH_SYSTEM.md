# IB Transactions 4-Month Based System

## Overview

This system has been upgraded from yearly-based IB transactions storage to a 4-month period-based approach. Instead of creating one table per year, the system now creates separate tables for each 4-month period, providing better data management and performance.

## 4-Month Periods

The year is divided into 3 periods of 4 months each:

-   **Q1**: January - April (`YYYY_q1`)
-   **Q2**: May - August (`YYYY_q2`)
-   **Q3**: September - December (`YYYY_q3`)

## Table Naming Convention

Tables are named using the format: `ib_transactions_YYYY_qX`

Examples:

-   `ib_transactions_2025_q1` (Jan-Apr 2025)
-   `ib_transactions_2025_q2` (May-Aug 2025)
-   `ib_transactions_2025_q3` (Sep-Dec 2025)

## Key Components

### 1. IBTransactionPeriodService

**Location**: `app/Services/IBTransactionPeriodService.php`

This service handles all period-related operations:

-   `getCurrentPeriod()` - Get current 4-month period
-   `getNextPeriod()` - Get next 4-month period
-   `getPreviousPeriod()` - Get previous 4-month period
-   `getTableName()` - Get table name for a period
-   `getPeriodDateRange()` - Get start/end dates for a period
-   `shouldCreateNextPeriodTable()` - Check if next period table should be created
-   `getPeriodName()` - Get human-readable period name

### 2. Updated IBTransactionService

**Location**: `app/Services/IBTransactionService.php`

Enhanced with 4-month period support:

-   `createIBTransactionTable4Month()` - Create table for specific period
-   `updateInPeriod()` - Update transaction in specific period
-   `isDuplicateInPeriod()` - Check duplicates in specific period
-   `getTransactionsForPeriod()` - Get transactions for specific period

### 3. Console Commands

#### CreateIBTransactionsTable4Month

**Command**: `ib:create-transactions-table-4month {period?} {--auto}`

Creates IB transactions table for a specific 4-month period.

```bash
# Create table for current period
php artisan ib:create-transactions-table-4month

# Create table for specific period
php artisan ib:create-transactions-table-4month 2025_q2

# Auto mode (creates next period table if needed)
php artisan ib:create-transactions-table-4month --auto
```

#### CopyIBTransactions4Month

**Command**: `copy:ib-transactions-4month {period?} {--auto} {--all-periods}`

Copies IB transactions from main transactions table to 4-month period tables.

```bash
# Copy for current period
php artisan copy:ib-transactions-4month

# Copy for specific period
php artisan copy:ib-transactions-4month 2025_q1

# Copy for all available periods
php artisan copy:ib-transactions-4month --all-periods

# Auto mode
php artisan copy:ib-transactions-4month --auto
```

#### ScheduleIBTransactions4Month

**Command**: `ib:schedule-4month-tasks`

Automatically handles table creation and data migration based on current date.

```bash
php artisan ib:schedule-4month-tasks
```

## Automatic Scheduling

The system is configured to run automatically via Laravel's task scheduler:

```php
// In app/Console/Kernel.php
$schedule->command('ib:schedule-4month-tasks')->daily()->at('02:00')->withoutOverlapping();
```

This command runs daily and:

1. Ensures current period table exists
2. Creates next period table if we're in the last month of current period
3. Copies transactions from main table to period tables
4. Handles end-of-period cleanup

## Migration

**File**: `database/migrations/2025_09_28_141230_setup_ib_transactions_4month_system.php`

Run the migration to set up initial tables:

```bash
php artisan migrate
```

This creates:

-   Current period table
-   Next period table
-   Previous period table (for data migration)

## Data Flow

1. **Transaction Creation**: New IB transactions are automatically stored in the current 4-month period table
2. **Period Transition**: When moving to a new period, the system automatically creates the new table
3. **Data Migration**: Existing transactions are copied from the main transactions table to appropriate period tables
4. **Cleanup**: Copied transactions are removed from the main transactions table

## Benefits

1. **Better Performance**: Smaller tables improve query performance
2. **Data Organization**: Clear separation of data by time periods
3. **Maintenance**: Easier to manage and archive old data
4. **Scalability**: System can handle large volumes of transactions more efficiently
5. **Automatic Management**: Fully automated table creation and data migration

## Usage Examples

### Creating Tables Manually

```bash
# Create table for Q1 2025
php artisan ib:create-transactions-table-4month 2025_q1

# Create table for Q2 2025
php artisan ib:create-transactions-table-4month 2025_q2
```

### Copying Data

```bash
# Copy all IB transactions for 2025 Q1
php artisan copy:ib-transactions-4month 2025_q1

# Copy all transactions for all periods
php artisan copy:ib-transactions-4month --all-periods
```

### Programmatic Usage

```php
use App\Services\IBTransactionPeriodService;
use App\Services\IBTransactionService;

// Get current period
$currentPeriod = IBTransactionPeriodService::getCurrentPeriod(); // e.g., "2025_q3"

// Get table name
$tableName = IBTransactionPeriodService::getTableName($currentPeriod); // "ib_transactions_2025_q3"

// Create table for period
IBTransactionService::createIBTransactionTable4Month($currentPeriod);

// Get transactions for period
$transactions = IBTransactionService::getTransactionsForPeriod($currentPeriod, ['user_id' => 123]);
```

## Monitoring

The system provides detailed logging and progress bars for data migration operations. Monitor the Laravel logs for any issues during automatic processing.

## Backward Compatibility

The original yearly-based system remains functional for existing data. The new 4-month system works alongside the old system, allowing for gradual migration.

## Troubleshooting

### Common Issues

1. **Table Creation Fails**: Ensure `ib_transactions_template` table exists
2. **Migration Errors**: Check database permissions and available disk space
3. **Scheduling Issues**: Verify cron jobs are running and Laravel scheduler is active

### Manual Recovery

If automatic processes fail, you can manually run:

```bash
# Ensure all tables exist
php artisan ib:create-transactions-table-4month 2025_q1
php artisan ib:create-transactions-table-4month 2025_q2
php artisan ib:create-transactions-table-4month 2025_q3

# Copy all data
php artisan copy:ib-transactions-4month --all-periods
```

## Performance Considerations

-   Tables are created with appropriate indexes based on the template
-   Chunk processing is used for large data migrations (500 records per chunk)
-   Memory optimization with garbage collection for large datasets
-   Transaction-based operations ensure data integrity

