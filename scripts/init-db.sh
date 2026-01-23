#!/usr/bin/env bash
set -e
docker compose up -d db
echo "DB starting... (init.sql will run automatically if DB is empty)"
sleep 5
echo "Database initialized (if empty)."
