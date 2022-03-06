**add containers associated IP to ifconfig lo0**

    sudo ifconfig lo0 alias 10.254.254.71

**generate migrations for existing db**

    composer require --dev bennett-treptow/laravel-migration-generator
    php artisan vendor:publish --provider="LaravelMigrationGenerator\LaravelMigrationGeneratorProvider"


git config --global user.email "cristian.visan@gmail.com"
git config --global user.name "Cristian Visan"

