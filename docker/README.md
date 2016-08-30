All environment configuration located in _docker_ folder.
1. Create docker-compose.yml from docker-compose.yml.dist and configure up to you
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
After installing composer will run bin/install script for creating data/* folders and set right permissions

###Frequently Asked Question(s)
 
 remove bluz containers
 ```
 $ docker-compose -p bluz -f docker/docker-compose.yml rm
 ```
 
 stop bluz containers 
 ```
 $ docker-compose -p bluz -f docker/docker-compose.yml stop
 ```
 
 show containers for bluz
 ```
 $ docker-compose -p bluz -f docker/docker-compose.yml ps
 ```

 get inside container
 ```
 $ docker exec -ti %container_name% %bash_commnad%
 ```
 
 run phpunit tests
 ```
 $ docker exec -ti bluz_php -u www-data phpunit
 ```
 