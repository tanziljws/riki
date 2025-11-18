#!/bin/bash

# Script untuk import SQL ke MySQL Railway
# Usage: ./import-sql.sh

SQL_FILE="galeriweb (3).sql"
DB_HOST="trolley.proxy.rlwy.net"
DB_PORT="49593"
DB_USER="root"
DB_PASS="BUNIgCsnyeQPwCuZpxLXrBPNYAJoolki"
DB_NAME="railway"

echo "Importing SQL file to MySQL Railway..."
echo "File: $SQL_FILE"
echo "Host: $DB_HOST:$DB_PORT"
echo "Database: $DB_NAME"
echo ""

# Check if SQL file exists
if [ ! -f "$SQL_FILE" ]; then
    echo "Error: SQL file '$SQL_FILE' not found!"
    echo "Please make sure the SQL file is in the current directory."
    exit 1
fi

# Import SQL file
mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" --protocol=TCP "$DB_NAME" < "$SQL_FILE"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ SQL file imported successfully!"
else
    echo ""
    echo "❌ Error importing SQL file. Please check the connection and try again."
    exit 1
fi

