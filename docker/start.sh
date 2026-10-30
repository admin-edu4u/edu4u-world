#!/bin/bash
set -e

# Railway MySQL often exposes MYSQLHOST / MYSQLDATABASE / MYSQLUSER / MYSQLPASSWORD / MYSQLPORT
# also support classic Laravel DB_* vars.
if [ -n "$MYSQLHOST" ]; then
  export DB_CONNECTION=mysql
  export DB_HOST="$MYSQLHOST"
  export DB_PORT="${MYSQLPORT:-3306}"
  export DB_DATABASE="$MYSQLDATABASE"
  export DB_USERNAME="$MYSQLUSER"
  export DB_PASSWORD="$MYSQLPASSWORD"
fi

# Prefer Railway's PORT if present (Apache stays on 80 inside container; Railway maps it)
php artisan config:clear || true
php artisan cache:clear || true
php artisan storage:link || true

exec apache2-foreground
