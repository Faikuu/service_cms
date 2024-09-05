#!/bin/bash
docker compose up -d
symfony server:start &
php bin/console tailwind:build --watch &
wait
echo "Server exited"
