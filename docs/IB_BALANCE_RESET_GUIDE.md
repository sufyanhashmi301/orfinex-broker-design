# IB Balance Reset & Quarter Migration Guide

## 🎯 **Purpose**

This script resets all users' `ib_balance` to 0, then recalculates the correct balance from the main `transactions` table before running the quarter migration. This ensures accurate IB balances before migrating to quarter-based tables.

## 📋 **Complete Execution Steps**

### **Step 1: Backup Your Database (CRITICAL!)**

```powershell
# Create a full database backup before starting
# Use your preferred backup method (mysqldump, phpMyAdmin, etc.)
```

### **Step 2: Test the Recalculation (Dry Run)**

```powershell
# See what would be changed without making actual changes
php artisan ib:recalculate-balances --dry-run --verbose

# Quick dry run without detailed output
php artisan ib:recalculate-balances --dry-run
```

### **Step 3: Execute the IB Balance Recalculation**

```powershell
# Run the actual recalculation
php artisan ib:recalculate-balances

# With verbose output to see each user update
php artisan ib:recalculate-balances --verbose

# With custom chunk size for large user bases
php artisan ib:recalculate-balances --chunk-size=500
```

### **Step 4: Verify the Results**

```powershell
# Check a few specific users' balances
php artisan tinker --execute="
\$user = App\Models\User::find(2);
echo 'User 2 ib_balance: ' . \$user->ib_balance . PHP_EOL;
\$user = App\Models\User::find(5);
echo 'User 5 ib_balance: ' . \$user->ib_balance . PHP_EOL;
"

# Check total IB balance across all users
php artisan tinker --execute="
\$totalUserIB = App\Models\User::sum('ib_balance');
\$totalMainIB = App\Models\Transaction::where('type', 'ib_bonus')->where('status', 'success')->sum('final_amount');
echo 'Total User IB Balance: $' . number_format(\$totalUserIB, 2) . PHP_EOL;
echo 'Total Main Table IB: $' . number_format(\$totalMainIB, 2) . PHP_EOL;
echo 'Difference: $' . number_format(abs(\$totalUserIB - \$totalMainIB), 2) . PHP_EOL;
"
```

### **Step 5: Run the Quarter Migration**

```powershell
# Now run the quarter migration with accurate balances
php artisan ib:auto-migration

# Or use manual high-performance migration
php artisan ib:high-performance-migration --chunk-size=2000 --batch-size=500 --memory-limit=2G
```

### **Step 6: Final Verification**

```powershell
# Test the complete system
php artisan ib:test-quarter-system

# Verify quarter integration
php artisan ib:test-quarter-integration

# Test new IB transaction creation
php artisan rebate:distribution
```

## 🔧 **Command Options**

### **Recalculate IB Balances Command:**

```powershell
php artisan ib:recalculate-balances [options]

Options:
  --dry-run              Show what would be updated without making changes
  --chunk-size=1000      Process users in chunks (default: 1000)
  --show-details         Show detailed progress for each user
```

### **Usage Examples:**

```powershell
# Safe test run
php artisan ib:recalculate-balances --dry-run

# Actual execution with progress details
php artisan ib:recalculate-balances --show-details

# Large database optimization
php artisan ib:recalculate-balances --chunk-size=500

# Combined options
php artisan ib:recalculate-balances --dry-run --show-details --chunk-size=100
```

## 📊 **What the Script Does**

### **Phase 1: Initial Statistics**

-   Counts total users
-   Shows users with current IB balance > 0
-   Calculates current total IB balance
-   Counts IB transactions in main table
-   Shows total successful IB amount

### **Phase 2: Reset All Balances**

-   Sets all users' `ib_balance` to 0
-   Shows how many users were affected

### **Phase 3: Recalculate from Main Table**

-   Queries all successful IB transactions (`type='ib_bonus'`, `status='success'`)
-   Groups by `user_id` and sums `final_amount`
-   Updates each user's `ib_balance` with correct total
-   Processes in chunks for performance

### **Phase 4: Verification**

-   Compares total user IB balance with main table total
-   Shows top 5 users by IB balance
-   Provides verification status

## 🎯 **Expected Output Example**

```
╔══════════════════════════════════════════════════════════════╗
║                IB Balance Recalculation Script              ║
║          Reset & Rebuild from Main Transactions             ║
╚══════════════════════════════════════════════════════════════╝

📊 Getting Initial Statistics
------------------------------
👥 Total users: 1,250
💰 Users with IB balance > 0: 85
💵 Current total IB balance: $125,430.50
📝 Total IB transactions in main table: 15,420
💎 Total successful IB amount in main table: $124,890.75

🔄 Resetting All IB Balances to Zero
------------------------------------
✅ Reset 1,250 users' ib_balance to 0

🧮 Recalculating IB Balances from Main Transactions
---------------------------------------------------
👥 Found 83 users with IB transactions
Processing chunk 1/1 (83 users)

📈 Final Results Summary
========================
✅ ACTUAL RESULTS:
Users updated: 83
Total IB amount redistributed: $124,890.75
IB transactions processed: 15,420

🔍 Verification Check
---------------------
💰 New total IB balance in users table: $124,890.75
📝 Total successful IB in main transactions: $124,890.75
✅ VERIFICATION PASSED: Balances match!

🏆 Top 5 Users by IB Balance:
  User 45 (trader1@example.com): $8,450.25
  User 23 (trader2@example.com): $6,230.50
  User 67 (trader3@example.com): $5,890.00
  User 12 (trader4@example.com): $4,560.75
  User 89 (trader5@example.com): $3,940.25

✅ IB balance recalculation completed successfully!
🚀 You can now run the quarter migration: php artisan ib:auto-migration
```

## 🚨 **Important Notes**

1. **Always run `--dry-run` first** to see what will be changed
2. **Backup your database** before running the actual command
3. **The script only counts successful transactions** (`status='success'`)
4. **Users with no IB transactions will have `ib_balance=0`**
5. **The verification check ensures data integrity**
6. **Run this before the quarter migration for accurate balances**

## 🔄 **Complete Migration Workflow**

```powershell
# 1. Backup database
# [Your backup command here]

# 2. Test IB balance recalculation
php artisan ib:recalculate-balances --dry-run

# 3. Execute IB balance recalculation
php artisan ib:recalculate-balances

# 4. Verify balances are correct
php artisan tinker --execute="echo 'Total IB: $' . number_format(App\Models\User::sum('ib_balance'), 2) . PHP_EOL;"

# 5. Run quarter migration
php artisan ib:auto-migration

# 6. Test the complete system
php artisan ib:test-quarter-system

# 7. Test new transaction creation
php artisan rebate:distribution
```

## ✅ **Success Indicators**

After successful execution:

-   ✅ All users' `ib_balance` accurately reflects their total IB earnings
-   ✅ Verification check shows balances match between users table and transactions table
-   ✅ No users have incorrect or missing IB balances
-   ✅ System is ready for quarter migration
-   ✅ Future IB transactions will correctly update both quarter tables and user balances

🎉 **Your IB balance system is now perfectly synchronized and ready for quarter migration!**
