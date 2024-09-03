#!/bin/sh

# Wait for the database to be ready (optional: you could add a more robust wait-for-db solution)
sleep 10

# Run Laravel-specific commands
php artisan key:generate --force
php artisan config:cache
php artisan route:cache

# Start Apache
apache2-foreground
