#!/bin/bash
set -e

# Ensure writable directory has correct permissions
mkdir -p /var/www/html/writable/cache
mkdir -p /var/www/html/writable/logs
mkdir -p /var/www/html/writable/session
mkdir -p /var/www/html/writable/uploads
mkdir -p /var/www/html/writable/uploads/covers
mkdir -p /var/www/html/writable/debugbar

# Set proper permissions
chown -R www-data:www-data /var/www/html/writable
chmod -R 777 /var/www/html/writable

# Force clean all npm caches
rm -rf /root/.npm 2>/dev/null || true
rm -rf /home/node/.npm 2>/dev/null || true
rm -rf /var/www/html/.npm 2>/dev/null || true

# Configure npm to use system temp directory instead of project directory
npm config set cache --global /tmp/npm-cache 2>/dev/null || true

# Execute the main command
exec "$@"
