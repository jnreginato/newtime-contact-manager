#!/bin/bash -e

if [[ ! -f .env ]]; then
  cp .env.example .env
  echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') INFO Environment settings file generated."
else
  echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') INFO .env already exists; keeping current values."
fi

echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') INFO: Enabling exec mode for /var/www/bin folder files..."
chmod -R +x /var/www/bin

echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') INFO: Cleaning PHP logs..."
rm -Rf /var/log/php && mkdir -m 777 /var/log/php
echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') INFO: Cleaning Supervisor logs..."
rm -Rf /var/log/supervisor && mkdir -m 777 /var/log/supervisor
echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') INFO: Cleaning tmp folder..."
rm -Rf /tmp/* /tmp/.[!.]* /tmp/..?*

echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') INFO: Installing dependencies..."
cd /var/www
composer install \
    --no-progress \
    --verbose \
    --no-interaction \
    --profile \
    --audit

composer dump-autoload --optimize --apcu

echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') INFO: Enabling development mode..."
composer development-mode-enable

if [[ -n "$(ls /etc/supervisor.d/*.conf 2>/dev/null)" ]]; then
    echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') INFO: Starting supervisor..."
    exec /usr/bin/supervisord -c /etc/supervisord.conf -n
else
    echo "$(date '+%Y-%m-%d %H:%M:%S,%3N') WARN: No supervisor .conf found. Container will idle..."
    exec tail -f /dev/null
fi
