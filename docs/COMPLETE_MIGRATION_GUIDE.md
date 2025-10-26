# Complete IB Transaction Migration Guide

## 🚀 Migration Commands for 25 Lakh (2.5M) Records

### Step 1: System Preparation and Testing

```powershell
# Test system integration first
php artisan ib:test-quarter-integration

# Create all necessary quarter tables (automatic detection)
php artisan ib:create-transactions-table-4month --auto

# Run Laravel migrations to ensure database is up-to-date
php artisan migrate

# Check current transaction count
php artisan tinker --execute="echo 'Total IB transactions: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;"
```

### Step 2: Data Validation and Backup

```powershell
# Backup your database before migration (CRITICAL!)
# Use your preferred backup method (mysqldump, phpMyAdmin, etc.)

# Test with small batch first (dry run)
php artisan ib:high-performance-migration --dry-run --chunk-size=100

# Verify quarter table structure
php artisan tinker --execute="
\$tables = DB::select('SHOW TABLES LIKE \"ib_transactions_%\"');
foreach(\$tables as \$table) {
    echo array_values((array)\$table)[0] . PHP_EOL;
}
"
```

### Step 3: Migration Execution (Choose ONE method)

#### Option A: Automated Migration (RECOMMENDED for 25 Lakh records)

```powershell
# Fully automated migration with progress monitoring
php artisan ib:auto-migration

# This command will:
# - Run high-performance migration in batches
# - Automatically restart when needed
# - Show progress updates
# - Handle errors gracefully
# - Continue until all records are migrated
```

#### Option B: Manual High-Performance Migration

```powershell
# Single-process migration with memory management
php artisan ib:high-performance-migration --chunk-size=2000 --batch-size=500 --memory-limit=2G

# If migration stops, resume with:
php artisan ib:high-performance-migration --chunk-size=2000 --batch-size=500 --memory-limit=2G --resume

# Monitor progress:
php artisan tinker --execute="echo 'Remaining: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;"
```

#### Option C: Parallel Migration (Windows may have issues)

```powershell
# Use only if you have verified it works on your Windows system
php artisan ib:parallel-migration --workers=4 --chunk-size=2000
```

### Step 4: Data Integrity and Verification

```powershell
# Check for any datetime issues in migrated data
php artisan ib:fix-transaction-dates

# Run comprehensive system test
php artisan ib:test-quarter-system

# Verify migration completion
php artisan tinker --execute="
echo 'Remaining IB transactions: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;
echo 'Migrated transactions in Q3 2025: ' . DB::table('ib_transactions_2025_q3')->count() . PHP_EOL;
echo 'Migrated transactions in Q4 2025: ' . DB::table('ib_transactions_2025_q4')->count() . PHP_EOL;
"

# Test IB balance updates
php artisan tinker --execute="
\$user = App\Models\User::find(2);
echo 'User ID 2 ib_balance: ' . \$user->ib_balance . PHP_EOL;
"
```

### Step 5: System Integration Testing

```powershell
# Test new IB transaction creation with quarter tables
php artisan rebate:distribution

# Verify admin panel display
# Visit: http://your-domain/backoffice/user/ib-bonus/2

# Verify user panel display
# Visit: http://your-domain/user/referral/history

# Test export functionality on both admin and user sides
```

### Step 6: Production Deployment

```powershell
# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📊 Performance Expectations for 25 Lakh Records

### Automated Migration (`ib:auto-migration`)

-   **Estimated Time**: 2-4 hours
-   **Memory Usage**: Optimized with garbage collection
-   **Batch Size**: 2000 records per batch
-   **Resumable**: Yes, automatically
-   **Monitoring**: Built-in progress updates

### Manual High-Performance Migration

-   **Estimated Time**: 3-5 hours
-   **Memory Usage**: 2GB limit recommended
-   **Batch Size**: 500-1000 records per batch
-   **Resumable**: Yes, with --resume flag
-   **Monitoring**: Manual progress checks

### Parallel Migration (if working on Windows)

-   **Estimated Time**: 1-2 hours
-   **Workers**: 4-8 recommended
-   **Memory Usage**: Higher due to multiple processes
-   **Resumable**: Limited
-   **Monitoring**: Per-worker progress

## 🛠 Troubleshooting Commands

```powershell
# Check Laravel logs
Get-Content storage\logs\laravel.log -Tail 50

# Check migration progress
php artisan tinker --execute="
echo 'Main table count: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;
\$quarterTables = ['ib_transactions_2024_q1', 'ib_transactions_2024_q2', 'ib_transactions_2024_q3', 'ib_transactions_2024_q4', 'ib_transactions_2025_q1', 'ib_transactions_2025_q2', 'ib_transactions_2025_q3', 'ib_transactions_2025_q4'];
\$total = 0;
foreach(\$quarterTables as \$table) {
    try {
        \$count = DB::table(\$table)->count();
        echo \$table . ': ' . \$count . PHP_EOL;
        \$total += \$count;
    } catch(Exception \$e) {
        echo \$table . ': Table not found' . PHP_EOL;
    }
}
echo 'Total migrated: ' . \$total . PHP_EOL;
"

# Fix datetime issues if found
php artisan ib:fix-transaction-dates

# Reset migration if needed (DANGER: Only in development)
# php artisan migrate:rollback --path=database/migrations/create_ib_transactions_tables.php
```

## 🎯 Quick Start for 25 Lakh Records

```powershell
# 1. Preparation
php artisan ib:test-quarter-integration
php artisan ib:create-transactions-table-4month --auto
php artisan migrate

# 2. Backup your database!

# 3. Run automated migration
php artisan ib:auto-migration

# 4. Verify completion
php artisan tinker --execute="echo 'Remaining: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;"

# 5. Fix any datetime issues
php artisan ib:fix-transaction-dates

# 6. Test the system
php artisan rebate:distribution
```

## 📈 Expected Results

After successful migration:

-   ✅ **Main `transactions` table**: 0 IB bonus records
-   ✅ **Quarter tables**: All 25 lakh records distributed by date
-   ✅ **Admin panel**: Shows last 1 year of transactions with filters
-   ✅ **User panel**: Shows last 3 months by default with filters
-   ✅ **New IB transactions**: Automatically saved to appropriate quarter tables based on transaction date
-   ✅ **User `ib_balance`**: Updated with each new IB transaction
-   ✅ **Export functionality**: Works with current filters on both sides
-   ✅ **Automatic table creation**: Next quarter tables created automatically
-   ✅ **Exception handling**: Comprehensive error recovery and logging
-   ✅ **Date-based routing**: Transactions saved to correct quarter based on their actual date

## 🚨 Important Notes

1. **Always backup your database before migration**
2. **Test on a staging environment first**
3. **Monitor system resources during migration**
4. **Use `ib:auto-migration` for hands-off migration**
5. **The migration is resumable if interrupted**
6. **Quarter tables are created automatically based on data**
7. **New IB transactions will automatically use quarter tables**
8. **System correctly handles transaction dates vs current dates**
9. **Comprehensive testing available with `php artisan ib:test-quarter-system`**
