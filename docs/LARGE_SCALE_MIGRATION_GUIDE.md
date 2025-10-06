# Large Scale IB Transactions Migration Guide

## Overview

This guide covers migrating large volumes of IB transactions (25+ lakh records) from the main transactions table to quarter-based tables with maximum efficiency and performance.

## 🚀 High-Performance Migration Commands

### 1. High-Performance Single-Process Migration

**Command**: `ib:high-performance-migration`

Optimized for large datasets with advanced memory management and database optimizations.

```bash
# Basic high-performance migration
php artisan ib:high-performance-migration

# Custom settings for 25 lakh records
php artisan ib:high-performance-migration \
    --chunk-size=2000 \
    --batch-size=500 \
    --memory-limit=1G

# Dry run to test performance
php artisan ib:high-performance-migration --dry-run

# Resume from specific transaction ID (if interrupted)
php artisan ib:high-performance-migration --resume-from-id=1000000
```

**Features**:

-   ✅ Optimized database settings for bulk operations
-   ✅ Memory management with garbage collection
-   ✅ Progress tracking with performance metrics
-   ✅ Resume capability for interrupted migrations
-   ✅ Automatic database optimization and restoration

### 2. Parallel Processing Migration

**Command**: `ib:parallel-migration`

Uses multiple worker processes for maximum throughput on multi-core systems.

```bash
# Parallel migration with 4 workers
php artisan ib:parallel-migration --workers=4

# High-performance parallel processing
php artisan ib:parallel-migration \
    --workers=8 \
    --chunk-size=5000

# Dry run for testing
php artisan ib:parallel-migration --dry-run --workers=4
```

**Features**:

-   ✅ Multiple parallel worker processes
-   ✅ Automatic work distribution by ID ranges
-   ✅ Real-time progress monitoring
-   ✅ Fault tolerance with worker status tracking
-   ✅ Optimal for multi-core servers

## 📊 Performance Optimization Settings

### Recommended Settings for 25 Lakh Records

| Setting        | Single Process | Parallel (4 workers) | Parallel (8 workers) |
| -------------- | -------------- | -------------------- | -------------------- |
| Chunk Size     | 2000           | 5000                 | 3000                 |
| Batch Size     | 500            | 1000                 | 750                  |
| Memory Limit   | 1G             | 512M per worker      | 256M per worker      |
| Estimated Time | 45-60 minutes  | 15-25 minutes        | 10-18 minutes        |

### Database Optimizations Applied

The high-performance migration automatically applies these optimizations:

```sql
-- Disable foreign key checks for faster inserts
SET FOREIGN_KEY_CHECKS=0;

-- Optimize for bulk inserts
SET SESSION sql_mode = "";
SET SESSION innodb_autoinc_lock_mode = 2;

-- Increase bulk insert buffer (256MB)
SET SESSION bulk_insert_buffer_size = 256 * 1024 * 1024;
```

## 🔧 Migration Strategy for 25 Lakh Records

### Phase 1: Preparation (5 minutes)

```bash
# 1. Test system readiness
php artisan ib:test-quarter-integration

# 2. Create quarter tables
php artisan ib:create-transactions-table-4month --auto

# 3. Dry run to estimate performance
php artisan ib:high-performance-migration --dry-run
```

### Phase 2: High-Performance Migration (15-60 minutes)

**Option A: Single Process (Recommended for stability)**

```bash
php artisan ib:high-performance-migration \
    --chunk-size=2000 \
    --batch-size=500 \
    --memory-limit=1G
```

**Option B: Parallel Processing (Recommended for speed)**

```bash
php artisan ib:parallel-migration \
    --workers=4 \
    --chunk-size=5000
```

### Phase 3: Verification (2 minutes)

```bash
# Verify migration completion
php artisan ib:test-quarter-integration

# Check remaining transactions
php artisan tinker --execute="echo 'Remaining: ' . App\Models\Transaction::where('type', 'ib_bonus')->count();"
```

## 📈 Performance Monitoring

### Real-Time Monitoring

Both commands provide real-time monitoring:

-   **Progress Bars**: Visual progress with ETA
-   **Memory Usage**: Peak memory tracking
-   **Processing Speed**: Records per second
-   **Statistics**: Processed, migrated, skipped counts

### Sample Output

```
🚀 High-Performance IB Transactions Migration
============================================
Memory Limit: 1G
Chunk Size: 2000
Batch Size: 500

📊 Pre-Migration Analysis
-------------------------
Total IB transactions to migrate: 2,500,000
Estimated memory per chunk: 4.00 MB
Estimated processing time: 83.33 minutes

🚄 High-Performance Migration
-----------------------------
 2500000/2500000 [============================] 100%
Memory usage: 156.32 MB | Chunks processed: 1250

📈 Migration Statistics
======================
Total processed: 2,500,000
Total migrated: 2,500,000
Total skipped: 0
Processing time: 45.67 minutes
Peak memory usage: 234.56 MB
Processing speed: 912.45 records/second
🎉 ALL TRANSACTIONS SUCCESSFULLY MIGRATED!
```

## 🛠️ Troubleshooting Large Migrations

### Common Issues and Solutions

#### 1. Memory Exhaustion

```bash
# Increase memory limit
php artisan ib:high-performance-migration --memory-limit=2G

# Or reduce chunk size
php artisan ib:high-performance-migration --chunk-size=1000
```

#### 2. Database Connection Timeout

```bash
# Add to database configuration
'mysql' => [
    'options' => [
        PDO::ATTR_TIMEOUT => 3600, // 1 hour
    ],
],
```

#### 3. Migration Interruption

```bash
# Resume from last processed ID
php artisan ib:high-performance-migration --resume-from-id=1500000
```

#### 4. Disk Space Issues

```bash
# Check available space before migration
df -h

# Monitor during migration
watch -n 5 'df -h'
```

### Performance Tuning

#### MySQL Configuration for Large Migrations

Add to `my.cnf` or `my.ini`:

```ini
[mysqld]
# Increase buffer sizes
innodb_buffer_pool_size = 2G
innodb_log_file_size = 512M
innodb_log_buffer_size = 64M

# Optimize for bulk operations
innodb_flush_log_at_trx_commit = 2
sync_binlog = 0
innodb_doublewrite = 0

# Increase timeouts
wait_timeout = 3600
interactive_timeout = 3600
```

#### PHP Configuration

```ini
; Increase memory and execution time
memory_limit = 2G
max_execution_time = 0
max_input_time = -1

; Optimize garbage collection
zend.enable_gc = 1
```

## 📋 Migration Checklist for 25 Lakh Records

### Pre-Migration

-   [ ] Backup database
-   [ ] Check available disk space (at least 2x current table size)
-   [ ] Verify MySQL configuration
-   [ ] Test with dry run
-   [ ] Schedule during low-traffic period

### During Migration

-   [ ] Monitor system resources (CPU, Memory, Disk)
-   [ ] Watch for database errors
-   [ ] Track progress and performance metrics
-   [ ] Keep backup of original data until verification

### Post-Migration

-   [ ] Verify all transactions migrated
-   [ ] Test MultiLevelRebateDistribution integration
-   [ ] Check quarter table integrity
-   [ ] Update application documentation
-   [ ] Archive old yearly tables if needed

## 🎯 Expected Performance Metrics

### For 25 Lakh (2.5 Million) Records

| Method               | Workers | Time      | Speed             | Memory        |
| -------------------- | ------- | --------- | ----------------- | ------------- |
| High-Performance     | 1       | 45-60 min | 800-1000 rec/sec  | 200-500 MB    |
| Parallel (4 workers) | 4       | 15-25 min | 1500-2500 rec/sec | 256 MB/worker |
| Parallel (8 workers) | 8       | 10-18 min | 2000-4000 rec/sec | 128 MB/worker |

### Factors Affecting Performance

-   **Server Specifications**: CPU cores, RAM, SSD vs HDD
-   **Database Configuration**: Buffer sizes, log settings
-   **Network Latency**: Database connection speed
-   **Concurrent Load**: Other database operations
-   **Data Complexity**: Transaction size and relationships

## 🚨 Safety Measures

### Automatic Safeguards

1. **Transaction Safety**: All operations wrapped in database transactions
2. **Duplicate Prevention**: Automatic duplicate detection and skipping
3. **Memory Management**: Garbage collection and memory monitoring
4. **Progress Tracking**: Resume capability for interrupted migrations
5. **Database Restoration**: Automatic restoration of optimized settings

### Manual Safety Steps

1. **Database Backup**: Always backup before migration
2. **Staging Test**: Test on staging environment first
3. **Gradual Migration**: Consider migrating in smaller batches if needed
4. **Monitoring**: Watch system resources during migration
5. **Rollback Plan**: Have a rollback strategy ready

## 🎉 Success Verification

After migration completion, verify success with:

```bash
# Check migration status
php artisan ib:test-quarter-integration

# Verify transaction counts
php artisan tinker --execute="
use App\Services\IBTransactionPeriodService;
use Illuminate\Support\Facades\DB;

// Count in main table
\$mainCount = DB::table('transactions')->where('type', 'ib_bonus')->count();
echo 'Main table: ' . \$mainCount . PHP_EOL;

// Count in quarter tables
\$quarterTotal = 0;
\$years = [2024, 2025]; // Adjust as needed
foreach (\$years as \$year) {
    \$periods = IBTransactionPeriodService::getYearPeriods(\$year);
    foreach (\$periods as \$period) {
        \$table = IBTransactionPeriodService::getTableName(\$period);
        if (Schema::hasTable(\$table)) {
            \$count = DB::table(\$table)->count();
            \$quarterTotal += \$count;
            echo \$table . ': ' . \$count . PHP_EOL;
        }
    }
}
echo 'Quarter tables total: ' . \$quarterTotal . PHP_EOL;
"
```

## 📞 Support

If you encounter issues during large-scale migration:

1. Check the migration logs for specific error messages
2. Verify system resources (memory, disk space, CPU)
3. Test with smaller chunk sizes
4. Use the resume functionality if migration was interrupted
5. Consider parallel processing for faster completion

The system is designed to handle enterprise-scale migrations efficiently and safely!
