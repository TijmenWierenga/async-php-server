<?php
namespace TijmenWierenga\Server;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server as HttpServer;
use React\Promise\Promise;
use React\Socket\Server as SocketServer;

/**
 * @author Tijmen Wierenga <t.wierenga@live.nl>
 */
class AsyncServer implements Server
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var RequestHandler
     */
    private $requestHandler;
    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * AsyncServer constructor.
     * @param Connection $connection
     * @param RequestBuilder $requestBuilder
     * @param RequestHandler $requestHandler
     */
    public function __construct(Connection $connection, RequestBuilder $requestBuilder, RequestHandler $requestHandler)
    {
        $this->connection = $connection;
        $this->requestBuilder = $requestBuilder;
        $this->requestHandler = $requestHandler;
    }

    public function run(): void
    {
        $loop = Factory::create();

        $server = new HttpServer(function (ServerRequestInterface $request) {
            return new Promise(function ($resolve, $reject) use ($request) {
                $request->getBody()->on('data', function ($data) use (&$request) {
                    $request = $this->requestBuilder->withData($request, $data);
                });

                $request->getBody()->on('end', function () use ($resolve, $request){
                    $response = $this->requestHandler->handle($request);
                    $resolve($response);
                });

                // an error occures e.g. on invalid chunked encoded data or an unexpected 'end' event
                $request->getBody()->on('error', function (Exception $exception) use ($resolve) {
                    // TODO: Return response based on Accept header and Exception data.
                    $response = new Response();
                    $resolve($response);
                });
            });
        });

        $socket = new SocketServer((string) $$this->connection, $loop);
        $server->listen($socket);

        $loop->run();
    }
}
