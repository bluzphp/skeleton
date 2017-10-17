Docker
======

## Setup containers

All environment configuration located in `docker` folder.
1. Create `docker-compose.yml` from `docker-compose.yml.dist` and configure up to you
2. Build containers
```
$ docker-compose -p bluz -f docker/docker-compose.yml build
```

3. Run containers
```
$ docker-compose -p bluz -f docker/docker-compose.yml up
```

4. Run composer install from php container
```
$ docker exec -ti bluz_php php composer.phar install
```

5. Setup permissions
```
$ docker exec -ti bluz_php setup_permissions
```

6. Run migrations and seed
```
$ docker exec -ti bluz_php bluzman db:migrate
$ docker exec -ti bluz_php bluzman db:seed:run
```

## Frequently Asked Question(s)
 
Show containers for bluz
```
$ docker-compose -p bluz -f docker/docker-compose.yml ps
```

Stop bluz containers 
```
$ docker-compose -p bluz -f docker/docker-compose.yml stop
```

Remove bluz containers
```
$ docker-compose -p bluz -f docker/docker-compose.yml rm
```

Run command inside container
```
$ docker exec -ti %container_name% %bash_command%
```

Run database migration
```
$ docker exec -ti bluz_php bluzman db:migrate
```

Run database seed
```
$ docker exec -ti bluz_php bluzman db:seed:run
```

Run tests
```
$ docker exec -ti bluz_php bluzman test
```
