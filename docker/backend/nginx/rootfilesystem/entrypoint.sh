#!/bin/sh
set -e

CERT_DIR="/etc/nginx/ssl"
CERT_KEY="$CERT_DIR/certificate.key"
CERT_CRT="$CERT_DIR/certificate.crt"

if [ ! -f "$CERT_CRT" ]; then
  echo "[INFO] Generating self-signed certificate..."
  mkdir -p "$CERT_DIR"
  openssl req -x509 -newkey rsa:2048 -nodes \
    -keyout "$CERT_KEY" \
    -out "$CERT_CRT" \
    -subj "/CN=localhost" \
    -days 365
else
  echo "[INFO] Certificate already exists."
fi

echo "[INFO] Starting NGINX..."
exec "$@"
