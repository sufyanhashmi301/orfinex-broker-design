# MT5 Database Connection Optimization

This document describes the comprehensive MT5 database connection optimization implemented to prevent timeout issues and system blocking.

## Problem Statement

The MT5 external database connection was experiencing timeout issues that caused the entire system to hang, affecting user experience and system reliability.

## Solution Overview

A comprehensive solution with multiple layers of protection:

### 1. Connection Timeout Configuration
- **Connection Timeout**: 5 seconds
- **Query Timeout**: 10 seconds  
- **Wait Timeout**: 30 seconds
- **Interactive Timeout**: 30 seconds
- **Read/Write Timeout**: 10 seconds

### 2. MT5DatabaseService (app/Services/MT5DatabaseService.php)

Centralized service for all MT5 database operations with:

- **Timeout Handling**: All queries execute with configurable timeouts
- **Circuit Breaker Pattern**: Automatically stops attempting connections after failures
- **Health Checks**: Regular connection health monitoring
- **Graceful Degradation**: Returns default values instead of hanging
- **Connection Pooling**: Optimized connection management
- **Error Logging**: Comprehensive error tracking and monitoring

### 3. Updated Components

#### DatabaseConfigServiceProvider
- Enhanced with timeout options and PDO attributes
- Disabled persistent connections to prevent hanging
- Added MySQL-specific timeout configurations

#### MultiLevelRebateDistribution Command
- Now uses MT5DatabaseService for resilient operations
- Skips processing when MT5 database is unavailable
- Continues processing other accounts if one fails

#### Helper Functions
- `get_mt5_account_balance()` - Uses resilient service
- `mt5_total_balance()` - Uses resilient service  
- `mt5_total_equity()` - Uses resilient service

### 4. Monitoring and Health Checks

#### Health Check Command
```bash
php artisan mt5-db:health-check
php artisan mt5-db:health-check --force
php artisan mt5-db:health-check --reset
```

#### Continuous Monitoring
```bash
php artisan mt5-db:monitor --interval=60 --max-failures=5
```

## Environment Configuration

Add these settings to your `.env` file:

```env
# MT5 Database Timeout Settings (seconds)
MT5_DB_TIMEOUT=10
MT5_DB_CONNECT_TIMEOUT=5
MT5_DB_WAIT_TIMEOUT=30
MT5_DB_INTERACTIVE_TIMEOUT=30
MT5_DB_READ_WRITE_TIMEOUT=10

# Optional: Circuit Breaker Settings
MT5_DB_CIRCUIT_BREAKER_THRESHOLD=3
MT5_DB_CIRCUIT_BREAKER_TIMEOUT=300
MT5_DB_HEALTH_CACHE_DURATION=60
```

## Key Features

### Circuit Breaker Pattern
- Automatically detects connection failures
- Temporarily disables connection attempts after threshold
- Prevents system from hanging on repeated failures
- Auto-recovery when connection is restored

### Graceful Degradation
- Returns sensible default values (0.0 for balances)
- Logs errors without stopping system operation
- Continues processing other operations when possible

### Caching Strategy
- Health status cached for 1 minute
- Connection failure status cached for 5 minutes
- Prevents repeated connection attempts during outages

### Comprehensive Logging
- Connection failures logged with context
- Slow query detection and logging
- Health check status tracking
- Performance monitoring

## Usage Examples

### In Controllers/Services
```php
use App\Services\MT5DatabaseService;

class YourController extends Controller
{
    protected MT5DatabaseService $mt5Service;
    
    public function __construct(MT5DatabaseService $mt5Service)
    {
        $this->mt5Service = $mt5Service;
    }
    
    public function getBalance($login)
    {
        return $this->mt5Service->getAccountBalance($login);
    }
}
```

### In Commands
```php
if (!$this->mt5DatabaseService->isConnectionAvailable()) {
    $this->warn('MT5 database unavailable, skipping...');
    return 0;
}

$result = $this->mt5DatabaseService->executeWithTimeout(function() {
    // Your database operation here
    return DB::connection('mt5_db')->table('mt5_deals')->get();
}, collect()); // Default value if timeout/failure
```

## Monitoring and Alerts

### Health Check Scheduling
Add to your `app/Console/Kernel.php`:

```php
$schedule->command('mt5-db:health-check')
         ->everyFiveMinutes()
         ->withoutOverlapping();
```

### Log Monitoring
Monitor these log entries:
- `MT5 database timeout detected`
- `MT5 database health check failed`
- `MT5 database unavailable, returning default value`

## Performance Benefits

1. **No More System Hangs**: Timeouts prevent indefinite blocking
2. **Faster Error Recovery**: Circuit breaker stops futile attempts
3. **Better User Experience**: System continues working during DB issues
4. **Proactive Monitoring**: Early detection of connection problems
5. **Resource Optimization**: Reduced connection overhead

## Troubleshooting

### Common Issues

1. **Connection Timeouts**
   - Check network connectivity to MT5 database
   - Verify database server is running
   - Review timeout settings in environment

2. **Circuit Breaker Activated**
   - Check database health: `php artisan mt5-db:health-check`
   - Reset if needed: `php artisan mt5-db:health-check --reset`
   - Monitor logs for root cause

3. **Performance Issues**
   - Monitor slow query logs
   - Adjust timeout values if needed
   - Check database server performance

### Reset Circuit Breaker
```bash
php artisan mt5-db:health-check --reset
```

### Force Health Check
```bash
php artisan mt5-db:health-check --force
```

## Configuration Files

- `config/mt5database.php` - MT5 database configuration
- `app/Services/MT5DatabaseService.php` - Core service
- `app/Providers/DatabaseConfigServiceProvider.php` - Connection setup
- `app/Console/Commands/MT5DatabaseHealthCheck.php` - Health monitoring
- `app/Console/Commands/MT5DatabaseMonitor.php` - Continuous monitoring

## Best Practices

1. **Always use MT5DatabaseService** for MT5 database operations
2. **Set appropriate timeouts** based on your network conditions
3. **Monitor health checks** regularly via scheduled commands
4. **Test circuit breaker** functionality in staging environment
5. **Review logs** for patterns in connection failures
6. **Use graceful degradation** - return defaults instead of errors
7. **Implement proper retry logic** with exponential backoff

This optimization ensures your application remains responsive and reliable even when the MT5 database experiences connectivity issues.
