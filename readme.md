TodoMvcPhp - Clean PHP
======================

## How to run project

1. CLI: `composer create-project vyvojarisobe/todomvcphp-php`
2. Change properties in PROJECT_ROOT/app/config/config.local.neon to your servers
3. Go to root of project and run in CLI: `composer install -o --no-dev`
4. Run in CLI: `php -S 127.0.0.1:80 -t www`

Dependencies
============

+ PHP >= 7.0
+ PDO
+ PDO_SQLite
+ Composer