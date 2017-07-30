# Asynchronous PHP Server
This package contains an easy to use asynchronous PHP server which you can setup in minutes. Get rid of bootstrapping your entire application on every request!

The server is build with the awesome [React PHP](https://github.com/reactphp) components which enables you to speed up your application.
Do all the necessary bootstrapping of your app only once, and keep it in memory as all requests are being handled in an asynchronous way.

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
docker run \
    --rm \
    -t \
    -d \
    -v ~/www/server:/var/www/html \
    --name async_php_server \
    php:7.1-fpm-alpine \
    php /var/www/html/example/demo-server.php
```

To check if the server is running, check the logs:
``` bash 
docker logs async_php_server
```
This should print: `Server is running on 0.0.0.0:9000`

To test the server, simply send a CURL command to the container:
``` bash 
docker exec async_php_server curl -H "Content-Type: application/json" -X POST -d '{"name":"tijmen","age":30}' http://localhost:9000
```
If everything is working correctly, you'll receive a `200 OK` JSON Response containing the posted data. 

## Usage
In order to run the asynchronous server you need to pass it three required instances:
* Connection
* RequestHandler
* Parser

### Connection
A connection is necessary for the server to know to bind to. Create a new Connection instance and specify the port and IP address:
```php
$connection = \TijmenWierenga\Server\Connection::init(9000, '0.0.0.0'); 
```

### RequestHandler
The RequestHandler is basically a wrapper for the application. The `handle` method receives the request and should return a response (even on an error).
You can find a very simplistic example of a RequestHandler below:
```php
class HelloWorld implements TijmenWierenga\Server\RequestHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, ['Content-Type' => 'text/plain'], "Hello World!");
    }
}
```
This RequestHandler will respond with "Hello World!" anytime it receives a request.

### Parser
The parser is used to parse the Request and Response body. A parser can simply format any given data into a specific format.
This package comes with a DefaultParser class which is a wrapper class for [nathanmac/parser](https://github.com/nathanmac/Parser).
Instantiate the DefaultParser like this:
``` php 
$parser = new TijmenWierenga\Server\DefaultParser(new \Nathanmac\Utilities\Parser\Parser());
```

### Bringing it all together
``` php
$server = new TijmenWierenga\Server\AsyncServer($connection, $requestHandler, $parser);
$server->run();
```

## Known issues
* The server can only handle a certain amount of content types
* The server cannot handle file uploads

## Docker
TODO