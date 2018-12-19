#!/usr/bin/env bash

# This script prepares all dependent files to be deployed to server as a single .tar file
# This script intended to be executed in Jenkins CI, but likelly to be extended by you.

set -e # Stop on error
set -x # Show commands being executed

# Downloading dependencies and building frontend
APP_ENV=prod composer install --no-dev --no-scripts --no-interaction  --ignore-platform-reqs --optimize-autoloader
#temporary install dev bundles to production (for fixtures...), also temporary added -ignore-platform-reqs for phpspreadsheet as Aurelijus suggested
#composer install --no-interaction --optimize-autoloader --ignore-platform-reqs

yarn
yarn run encore production

# <-- This is a good place to add custom commands for your project

# Generating deployment artifact (one file with everything you need to be deployed on the server)
tar czf project.tar.gz --owner 0 --group 0 --anchored $( \
    ls -a | tail -n +3 \
    | grep -v "node_modules" \
    | grep -v ".git" \
    | grep -v ".deploy" \
    | grep -v ".docker" \
    | grep -v ".idea" \
)
