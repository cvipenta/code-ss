# My Case - from Symfony to Laravel in few weeks

**Prerequisites**
- being a Symfony developer for more than 5 years (5+)
- using during the time the `2.*, 3.*, 4.* and 5.*` versions of symfony (from `2.4` to `5.4`)
- using symfony for backend and frontend
- covering almost all the components from symfony (more of them in the experimental phase: eg: messenger, mailer 4.3)
- following their best practice recommendations (https://symfony.com/doc/current/best_practices.html)

- using symfony with other technologies (mysql, rabbitmq, memcached, elasticsearch etc.)
- using symfony with `doctrine` (agreed database orm) but also using it with other connectors as `adodb`
- using symfony with `twig` templating system

- using applications / modules based on symfony components / bundles: 
  - OroCRM
  - ApiPlatform
  - EasyAdmin

- using symfony as backend for React Js frontend
- being connected with Symfony ecosystem 
  - subscribed to https://symfony.com/blog/
  - subscribed to https://symfonycasts.com (former https://kpnuniversity.com)
  - having the opportunity to attend to SymfonyCon events in 2018 and 2019 and see them in person

- using symfony components in legacy applications (some of them vanilla php) eg: cache, finder, filesystem, twig etc.

**Opening Laravel documentation**

Currently, at https://laravel.com/docs/9.x

- see configuration
- see the directory structure, open the files to see what's inside
- see Architecture Concepts
- see usage of Facades https://laravel.com/docs/9.x/facades#facade-class-reference

**First days thoughts**
- looks from the same language family, same concepts are doing the same but have a different name
- it's using some symfony components
- attending to https://laracasts.com/series/laravel-8-from-scratch (first time just pass through the whole course without practising)

- better docker integration (symfony is trying to do the same in the last releases)
- simpler routing system
- facades for everything
- `php artisan *` with tones of ready to use commands, try few of them to see the arguments and the output

- see `.env` file and see how `config/*` files are using them, moreover `docker-compose.yaml` being at the same level is using the same `.env`

- use the first `php artisan make:*` commands to practice

- how do you say 'something in Symfony' in Laravel: Controller, Entity, Messenger, Debug console
- study the templating system (blade): discover similarities and the differences (php code usage, components, partials)

- new buzzwords :)

#First project (practice)

## Cover the basics and the Architecture Concepts
- install the project as described in documentation using docker containers and Sail 
    (few hours of setup on Windows due to a custom setup)
- attending 2nd time to https://laracasts.com/series/laravel-8-from-scratch 
    (this time stopping and trying in my project)
- make the first models, routes, controllers, migrations, seeders, factories using `artisan make:*`
- install `itsgoingd/clockwork`
- install `laravel/breeze`

@ routing - see the differences and extra features  
@ middleware - global, registering custom
@ request / @response 
@ session
@ validation


## Digging deeper

**About Database and Eloquent ORM**
- use `php artisan migration:*` console commands (up, down, rollback, fresh)
- make the first queries in controllers
- define the first relations between models
- use custom primary_key
- use pagination
- use custom seeder and model factory seeder
- create or update db entries

- use `php artisan tinker` instead of dumping output

**About Redis in Laravel**
- find about clients `phpredis` or `predis`
- interact with redis using Redis facade (get, set, incr/by, rpush, blpop)
- test the same commands in container with `redis-cli` (`docker exec -it redis_container_name redis-cli`)
- Pipelining Commands
- Redis::publish / Redis::subscribe 
- Wildcard Subscriptions (Redis::psubscribe)

**About Queues and Cache**

Attend the courses below and read the documentation related:
  - https://laracasts.com/series/laravel-queue-mastery
  - https://laracasts.com/series/learn-laravel-and-redis-through-examples
  - https://laracasts.com/series/learn-laravel-horizon

1. About Laravel **Queues** with **database** and **Redis**
   - make first job `php artisan make:job JobName`
   - dispatch messages to database
   - dispatch messages to redis (specifying connection `redis` and queue name `custom_queue_name` on dispatch)
   - dispatch messages with parameters 
   - learn how to consume them from command line `php artisan queue:work redis --queue=custom_queue_name`
   - see the messages in redis-cli
   - play with `JobName::handle` 
   - play with `JobName::middleware` 
   - play with public properties that may configure / overwrite different options on job 

2. Using Laravel **Cache** with **filesystem** and **Redis**




