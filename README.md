
Useful stuff during development:


- bin/console doctrine:database:create --if-not-exists
- bin/console doctrine:schema:drop --force --full-database
- bin/console doctrine:migration:diff
- bin/console doctrine:migration:migrate
- bin/console doctrine:fixtures:load
- bin/console doctrine:fixtures:load --append
- bin/console doctrine:fixtures:load --env=dev


Other useful info:
- https://github.com/nfqakademija/docker/blob/master/docs/setup-xdebug.md
