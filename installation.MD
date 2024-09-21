npm install
composer update
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate

php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force


