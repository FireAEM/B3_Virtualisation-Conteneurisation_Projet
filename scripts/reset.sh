#!/usr/bin/env bash
set -e
# Arrête et supprime conteneurs + volumes définis dans compose
docker compose down -v
# Relance propre
docker compose up -d --build
echo "Environment reset and rebuilt."
