# Windows PowerShell Migration Commands

## ⚠️ **PowerShell Syntax Note**

PowerShell doesn't support backslash (`\`) line continuation like Linux/bash. Use these correct formats:

## 🚀 **Ready-to-Use Commands for 25 Lakh Records**

### **1. Migration Menu (Start Here)**

```powershell
php artisan ib:migration-menu
```

### **2. Test System Integration**

```powershell
php artisan ib:test-quarter-integration
```

### **3. Automated Migration (RECOMMENDED - New!)**

```powershell
# Fully automated migration with progress monitoring
php artisan ib:auto-migration

# This command will:
# - Run high-performance migration automatically
# - Show progress updates every batch
# - Handle errors and restart if needed
# - Continue until all records are migrated
# - No manual intervention required
```

### **4. High-Performance Migration (Manual Control)**

```powershell
# Dry run first (test without changes)
php artisan ib:high-performance-migration --dry-run --chunk-size=2000 --memory-limit=1G

# Actual migration
php artisan ib:high-performance-migration --chunk-size=2000 --batch-size=500 --memory-limit=1G

# Resume if interrupted
php artisan ib:high-performance-migration --resume --chunk-size=2000 --memory-limit=1G
```

### **5. Parallel Migration (Windows Issues Reported)**

```powershell
# ⚠️ WARNING: May not delete records properly on Windows
# Test first with dry run
php artisan ib:parallel-migration --dry-run --workers=4 --chunk-size=5000

# Use only if verified working on your system
php artisan ib:parallel-migration --workers=4 --chunk-size=5000
```

## 📊 **Performance Recommendations for Your System**

### **For 25 Lakh (2.5 Million) Records:**

| Method                  | Command                          | Estimated Time | Reliability | Recommendation |
| ----------------------- | -------------------------------- | -------------- | ----------- | -------------- |
| **Automated Migration** | `php artisan ib:auto-migration`  | **2-4 hours**  | **Highest** | **✅ BEST**    |
| **High-Performance**    | Manual high-performance commands | 3-5 hours      | High        | ✅ Good        |
| **Parallel (Windows)**  | Parallel migration commands      | 1-2 hours      | **Issues**  | ⚠️ Risky       |

## 🔧 **Step-by-Step Migration Process**

### **Phase 1: Preparation (5 minutes)**

```powershell
# 1. Check current system status
php artisan ib:migration-menu

# 2. Test system integration
php artisan ib:test-quarter-integration

# 3. Create quarter tables (auto-detects needed quarters)
php artisan ib:create-transactions-table-4month --auto

# 4. Run Laravel migrations
php artisan migrate

# 5. Check current transaction count
php artisan tinker --execute="echo 'Total IB transactions: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;"
```

### **Phase 2: Migration (Choose ONE method)**

**Option A: Automated Migration (RECOMMENDED)**

```powershell
# Fully automated - just run and wait
php artisan ib:auto-migration
```

**Option B: Manual High-Performance Migration**

```powershell
# Manual control with monitoring
php artisan ib:high-performance-migration --chunk-size=2000 --batch-size=500 --memory-limit=2G

# Monitor progress in another window:
php artisan tinker --execute="echo 'Remaining: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;"
```

**Option C: Parallel Migration (Use with caution on Windows)**

```powershell
# Test first
php artisan ib:parallel-migration --dry-run --workers=4 --chunk-size=2000

# Run if test passes
php artisan ib:parallel-migration --workers=4 --chunk-size=2000
```

### **Phase 3: Data Integrity & Verification (5 minutes)**

```powershell
# Fix any datetime format issues
php artisan ib:fix-transaction-dates

# Verify migration completion
php artisan tinker --execute="
echo 'Remaining IB transactions: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;
echo 'Q3 2025 migrated: ' . DB::table('ib_transactions_2025_q3')->count() . PHP_EOL;
echo 'Q4 2025 migrated: ' . DB::table('ib_transactions_2025_q4')->count() . PHP_EOL;
"

# Test new IB transaction creation
php artisan rebate:distribution

# Final system test
php artisan ib:test-quarter-integration
```

### **Phase 4: System Integration (2 minutes)**

```powershell
# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Test admin panel: /backoffice/user/ib-bonus/2
# Test user panel: /user/referral/history
```

## 🛠️ **Troubleshooting Commands**

### **Check Migration Progress**

```powershell
# Monitor remaining transactions
php artisan tinker --execute="echo 'Remaining: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;"

# Check migrated data by quarter
php artisan tinker --execute="
\$quarters = ['2024_q1', '2024_q2', '2024_q3', '2024_q4', '2025_q1', '2025_q2', '2025_q3', '2025_q4'];
\$total = 0;
foreach(\$quarters as \$q) {
    try {
        \$count = DB::table('ib_transactions_' . \$q)->count();
        echo 'ib_transactions_' . \$q . ': ' . \$count . PHP_EOL;
        \$total += \$count;
    } catch(Exception \$e) {
        echo 'ib_transactions_' . \$q . ': Not found' . PHP_EOL;
    }
}
echo 'Total migrated: ' . \$total . PHP_EOL;
"
```

### **Resume Interrupted Migration**

```powershell
# Resume high-performance migration
php artisan ib:high-performance-migration --resume --chunk-size=2000

# Or restart automated migration (it will skip already migrated records)
php artisan ib:auto-migration
```

### **Fix Common Issues**

```powershell
# Fix datetime format issues
php artisan ib:fix-transaction-dates

# Check Laravel logs for errors
Get-Content storage\logs\laravel.log -Tail 50

# Test system integration after fixes
php artisan ib:test-quarter-integration
```

## 💡 **PowerShell Multi-line Alternative**

If you prefer multi-line commands, use backticks (`) in PowerShell:

```powershell
php artisan ib:high-performance-migration `
    --chunk-size=2000 `
    --batch-size=500 `
    --memory-limit=2G

php artisan ib:parallel-migration `
    --workers=4 `
    --chunk-size=2000
```

## 🎯 **Quick Start for 25 Lakh Records**

**Easiest & Most Reliable (RECOMMENDED):**

```powershell
# 1. Prepare system
php artisan ib:test-quarter-integration
php artisan ib:create-transactions-table-4month --auto
php artisan migrate

# 2. Run automated migration
php artisan ib:auto-migration

# 3. Fix any issues and verify
php artisan ib:fix-transaction-dates
```

## ✅ **Success Verification**

After migration, you should see:

```powershell
# Check completion
php artisan tinker --execute="echo 'Remaining: ' . App\Models\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;"
# Should show: "Remaining: 0"

# Test new transaction creation
php artisan rebate:distribution
# Should create transaction in appropriate quarter table

# Test system integration
php artisan ib:test-quarter-integration
# Should show all green checkmarks
```

## 🚨 **Important Notes for Windows**

1. **Use Automated Migration** (`ib:auto-migration`) for best results on Windows
2. **Parallel migration may have issues** on Windows - use with caution
3. **Always backup database** before starting migration
4. **Monitor system resources** during migration
5. **Keep PowerShell window active** to prevent system sleep
6. **Close unnecessary applications** to free memory
7. **Run during off-peak hours** for best performance

## 🔄 **New Features Added**

-   ✅ **Automated Migration**: `php artisan ib:auto-migration` - Fully hands-off migration
-   ✅ **Datetime Fixes**: `php artisan ib:fix-transaction-dates` - Fixes any datetime issues
-   ✅ **Resume Capability**: High-performance migration can resume if interrupted
-   ✅ **Quarter-based Storage**: New IB transactions automatically use quarter tables
-   ✅ **IB Balance Updates**: User `ib_balance` column updated with each new transaction
-   ✅ **Admin & User Interfaces**: Updated to show data from quarter tables with filters
-   ✅ **Export Functionality**: Works with current filters on both admin and user sides

Your system is now ready to handle 25 lakh transactions efficiently! 🚀
