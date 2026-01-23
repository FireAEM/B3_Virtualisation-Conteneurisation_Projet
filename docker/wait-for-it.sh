#!/usr/bin/env bash
set -e

# Récupération des arguments
hostport="$1"
shift
cmd="$@"

# Exemple : wait-for-it.sh db:3306 -- apache2-foreground
# - $1 = db:3306 → l’adresse du service MySQL
# - shift → enlève le premier argument
# - $@ = apache2-foreground → la commande à lancer une fois MySQL prêt

# Séparation du host et du port
IFS=':' read -r host port <<< "$hostport"

# Timeout configurable
timeout=${WAIT_FOR_IT_TIMEOUT:-30}

# Message d’attente
echo "Waiting for $host:$port (timeout ${timeout}s)..."

# Boucle d’attente
for i in $(seq 1 $timeout); do
    if nc -z "$host" "$port" 2>/dev/null; then # Test si MySQL répond
        echo "$host:$port is available"
        exec $cmd # Si MySQL est prêt, on lance Apache
    fi
    sleep 1 # Sinon, on attend 1 seconde
done
echo "Timeout waiting for $host:$port" >&2
exit 1
