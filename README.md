# Asynchronous PHP Server

## Installation
There are two ways to install the server:
### Manual
Install the repository using GIT:
``` bash
git clone https://github.com/TijmenWierenga/async-php-server.git
```
### Composer
``` bash
composer require tijmen-wierenga/async-php-server
```

## Example
The repository comes with an example server which you can run locally. To run the example server simply run it in php:
``` bash
php example/demo-server.php 
```

Or run it with Docker (make sure you mount the correct volume to the container):
``` bash
docker run --rm -v ~/www/server:/var/www/html php:7.1-fpm-alpine php /var/www/html/example/demo-server.php
```

To check if the server is running, check the logs:
``` bash 
CID=$(docker ps -qf "ancestor=php:7.1-fpm-alpine")
docker logs $CID
```
This should print: `Server is running on 0.0.0.0:9000`

To test the server, simply send a CURL command to the container:
``` bash 
CID=$(docker ps -qf "ancestor=php:7.1-fpm-alpine")
docker exec $CID curl -H "Content-Type: application/json" -X POST -d '{"name":"tijmen","age":30}' http://localhost:9000
```

## Usage
TODO
## Docker
TODO