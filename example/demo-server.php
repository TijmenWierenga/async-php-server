<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Here we create an anonymous class implementing the RequestHandlerInterface.
 * The RequestHandler will just return a simple JSON response containing a simple message and the request data.
 */
$requestHandler = new class implements \TijmenWierenga\Server\RequestHandler {
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        return new \React\Http\Response(200, [
            'Content-Type' => 'application/json'
        ], json_encode([
            "message" => "Handled request",
            "data" => $request->getParsedBody()
        ]));
    }
};

/**
 * Initialize the connection with the default values.
 */
$connection = \TijmenWierenga\Server\Connection::init(9000);

/**
 * Instantiate a simple parser.
 */
$parser = new \TijmenWierenga\Server\DefaultParser(new \Nathanmac\Utilities\Parser\Parser());

/**
 * Start the server.
 */
$server = new \TijmenWierenga\Server\AsyncServer($connection, $requestHandler, $parser);
echo "Server is running on {$connection}" . PHP_EOL;
$server->run();
