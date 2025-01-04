#!/bin/bash
# wait-for-it.sh: Waits for a service to be available

set -e

host="$1"
port="$2"
shift 2
cmd="$@"

until nc -z "$host" "$port"; do
  echo "Waiting for $host:$port to be available..."
  sleep 2
done

>&2 echo "$host:$port is available. Proceeding..."
exec $cmd
