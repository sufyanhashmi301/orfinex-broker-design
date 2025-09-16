# Cryptocurrency Gateway Seeders

This document describes the cryptocurrency gateway seeders created to update supported currencies for payment gateways in the application.

## Overview

Two comprehensive seeders have been created to manage supported cryptocurrencies:

1. **NowPaymentsCurrenciesSeeder** - Updates NOWPayments gateway with 300+ supported cryptocurrencies
2. **CoinPaymentsCurrenciesSeeder** - Updates CoinPayments gateway with 200+ supported cryptocurrencies

## Seeders Created

### 1. NowPaymentsCurrenciesSeeder.php

**Purpose**: Updates the `gateways` table with NOWPayments supported cryptocurrencies

**Features**:

-   Comprehensive list of 300+ cryptocurrencies supported by NOWPayments
-   Includes major cryptocurrencies, DeFi tokens, stablecoins, gaming tokens, and more
-   Database transaction support for data integrity
-   Error handling with rollback capability
-   Updates existing gateway or creates new one if doesn't exist

**Currency Categories Included**:

-   Major Cryptocurrencies (BTC, ETH, XRP, etc.)
-   Stablecoins (USDT, USDC, DAI, etc.)
-   DeFi Tokens (UNI, AAVE, COMP, etc.)
-   Layer 2 Solutions (MATIC, AVAX, etc.)
-   Gaming/NFT Tokens (AXS, MANA, SAND, etc.)
-   Meme Coins (DOGE, SHIB, etc.)
-   Privacy Coins (XMR, ZEC, DASH, etc.)

### 2. CoinPaymentsCurrenciesSeeder.php

**Purpose**: Updates the `gateways` table with CoinPayments officially supported cryptocurrencies

**Features**:

-   Official list of 59 cryptocurrencies supported by CoinPayments v2
-   Based on official documentation: https://www.coinpayments.net/v2-supported-coins
-   Uses exact currency codes as listed in their documentation
-   Includes network-specific tokens (ERC-20, BEP-20, TRC-20, etc.)
-   Database transaction support with error handling
-   Automatic duplicate removal and alphabetical sorting

**Currency Categories Included**:

-   Native Cryptocurrencies (BTC, ETH, XRP, BCH, LTC, etc.)
-   ERC-20 Tokens (USDT.ERC20, USDC.ERC20, DAI.ERC20, etc.)
-   BEP-20 Tokens (BNB.BSC, USDT.BEP20, CAKE.BEP20, etc.)
-   TRC-20 Tokens (USDT.TRC20, TON.TRC20, etc.)
-   BASE Network Tokens (ETH.BASE, USDC.BASE, etc.)
-   Polygon Network Tokens (USDC.POL, USDT.POL, etc.)
-   Solana Network Tokens (USDC.SOL, USDT.SOL, etc.)

## Usage Instructions

### Running Individual Seeders

```bash
# Run NOWPayments seeder
php artisan db:seed --class=NowPaymentsCurrenciesSeeder

# Run CoinPayments seeder
php artisan db:seed --class=CoinPaymentsCurrenciesSeeder
```

### Running Both Seeders

```bash
# Add to DatabaseSeeder.php
$this->call([
    NowPaymentsCurrenciesSeeder::class,
    CoinPaymentsCurrenciesSeeder::class,
]);

# Then run
php artisan db:seed
```

## Database Impact

### What the Seeders Do

1. **Check for existing gateway**: Looks for existing gateway with the respective gateway_code
2. **Update or Create**: Updates existing gateway or creates new one
3. **Set supported_currencies**: Updates the `supported_currencies` JSON field with comprehensive currency list
4. **Configure defaults**: Sets default credentials structure and gateway settings
5. **Transaction safety**: Uses database transactions to ensure data integrity

### Gateway Configuration

Both seeders create/update gateways with:

-   **Status**: `false` (disabled by default - admin needs to configure)
-   **Type**: `auto` (automatic payment processing)
-   **Credentials**: Empty structure ready for admin configuration
-   **Supported Currencies**: Comprehensive JSON array of currency codes
-   **Default Currency**: USD
-   **Withdrawal Support**: Configurable

## Security Considerations

1. **Default Status**: Gateways are created with `status = false` to prevent accidental activation
2. **Empty Credentials**: No sensitive data is hardcoded - admin must configure
3. **Transaction Safety**: Database transactions ensure data integrity
4. **Error Handling**: Proper exception handling with rollback on errors

## Maintenance

### Updating Currency Lists

To update the supported currencies:

1. Edit the respective seeder file
2. Update the currency array with new/removed currencies
3. Run the seeder again to update the database

### Adding Live API Integration

Both seeders include placeholder methods for fetching live currency data:

-   `NowPaymentsCurrenciesSeeder::fetchLiveCurrencies()`
-   `CoinPaymentsCurrenciesSeeder::fetchLiveCurrencies()`

These can be implemented to fetch real-time currency lists from the respective APIs.

## Gateway Integration

After running the seeders:

1. **Configure Credentials**: Admin must add API keys and secrets
2. **Enable Gateway**: Set `status = true` in admin panel
3. **Test Integration**: Verify payment processing works correctly
4. **Configure Limits**: Set minimum/maximum deposit amounts
5. **Set Fees**: Configure transaction fees if needed

## Supported Currencies Count

-   **NOWPayments**: 300+ cryptocurrencies
-   **CoinPayments**: 59 officially supported cryptocurrencies (exact from documentation)
-   **Combined**: 350+ unique cryptocurrencies (after deduplication)

## Files Created

1. `database/seeders/NowPaymentsCurrenciesSeeder.php`
2. `database/seeders/CoinPaymentsCurrenciesSeeder.php`
3. `database/seeders/README_CRYPTO_SEEDERS.md` (this file)

## Next Steps

1. Run the seeders to populate/update the database
2. Configure gateway credentials in admin panel
3. Test payment processing with small amounts
4. Enable gateways for production use
5. Monitor transaction processing and update currency lists as needed

## Support

For issues or questions regarding these seeders:

1. Check Laravel logs for detailed error messages
2. Verify database connectivity and permissions
3. Ensure the `gateways` table exists with proper schema
4. Contact development team for technical support
