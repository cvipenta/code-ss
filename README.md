# Development tips
**Inspiration**
https://github.com/JeffreyWay/Laravel-From-Scratch-Blog-Project

**Generate migrations for existing db**

    composer require --dev bennett-treptow/laravel-migration-generator
    php artisan vendor:publish --provider="LaravelMigrationGenerator\LaravelMigrationGeneratorProvider"


# Docker setup

### Use provided docker-compose file

**set ip alias on Windows**
set in docker .env `COMPOSE_PROJECT_NAME` value (eg: code_laravel). In this case is shared with app .env

https://help.hcltechsw.com/onetest/hclonetestperformance/10.1/com.ibm.rational.test.lt.doc/topics/tconfigip_win.html

**Add or remove alias to vEthernet (WSL) using PS as administrator**

`netsh -c Interface ip add address name="vEthernet (WSL)" addr=172.22.160.2 mask=255.255.240.0`

`netsh -c Interface ip delete address name="vEthernet (WSL)" addr=172.22.160.2`

**Add the Ip alias to c:\Windows\System32\Drivers\etc\hosts**

    172.22.160.2 <COMPOSE_PROJECT_NAME>.web <COMPOSE_PROJECT_NAME>.mysql <COMPOSE_PROJECT_NAME>.memcached <COMPOSE_PROJECT_NAME>.redis`
    eg: 
    172.22.160.2 code_laravel.web code_laravel.mysql code_laravel.memcached code_laravel.redis`

**set ip alias on OSX**

    sudo ifconfig lo0 alias 10.254.254.71



# Miscellaneous
    git config --global user.email "email@domain.com"
    git config --global user.name "Firstname Lastname"
