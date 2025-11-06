#!/bin/bash -e

DB_PATH="/var/sqlite/database.sqlite"
TABLE_SQL="CREATE TABLE IF NOT EXISTS contacts (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  surname TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE
);"

# Colored logging functions
info()  { echo -e "\033[1;34m[INFO]\033[0m $1"; }
warn()  { echo -e "\033[1;33m[WARN]\033[0m $1"; }
error() { echo -e "\033[1;31m[ERROR]\033[0m $1"; }

info "Checking for SQLite database at: $DB_PATH"

if [ ! -f "$DB_PATH" ]; then
  info "Database not found. Creating new SQLite database..."
  mkdir -p "$(dirname "$DB_PATH")"
  sqlite3 "$DB_PATH" "$TABLE_SQL" && info "Database initialized with table 'users'."
else
  warn "Database already exists, skipping initialization."
fi

info "Listing current SQLite databases..."
ls -lh /var/sqlite/*.sqlite || warn "No .sqlite files found."

info "Container ready. Keeping process alive for persistence..."
tail -f /dev/null
