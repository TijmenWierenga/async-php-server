<?php
namespace TijmenWierenga\Server;

use Exception;
use Psr\Http\Message\ResponseInterface;
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
     * @var Parser
     */
    private $parser;

    /**
     * AsyncServer constructor.
     * @param Connection $connection
     * @param RequestHandler $requestHandler
     * @param Parser $parser
     */
    public function __construct(Connection $connection, RequestHandler $requestHandler, Parser $parser)
    {
        $this->connection = $connection;
        $this->requestHandler = $requestHandler;
        $this->parser = $parser;
    }

    public function run(): void
    {
        $loop = Factory::create();

        $server = new HttpServer(function (ServerRequestInterface $request) {
            return new Promise(function ($resolve, $reject) use ($request) {
                $request->getBody()->on('data', function ($data) use (&$request) {
                    $request = $this->parseRequestBody($request, $data);
                });

                $request->getBody()->on('end', function () use ($resolve, $request){
                    $response = $this->requestHandler->handle($request);
                    $resolve($response);
                });

                $request->getBody()->on('error', function (Exception $exception) use ($resolve, $request) {
                    $response = $this->parseException($exception, $request);
                    $resolve($response);
                });
            });
        });

        $socket = new SocketServer((string) $$this->connection, $loop);
        $server->listen($socket);

        $loop->run();
    }

    /**
     * @param ServerRequestInterface $request
     * @param mixed $data
     * @return ServerRequestInterface
     */
    private function parseRequestBody(ServerRequestInterface $request, $data): ServerRequestInterface
    {
        if ($request->hasHeader('Content-Type')) {
            $format = $request->getHeader('Content-Type')[0];
        } else {
            $format = 'application/json';
        }

        $parsedBody = $this->parser->parse($data, $format);

        return $request->withParsedBody($parsedBody);
    }

    private function parseException(Exception $exception, ServerRequestInterface $request): ResponseInterface
    {
        if ($request->hasHeader('Accept')) {
            $format = $request->getHeader('Accept')[0];
        } else {
            $format = 'application/json';
        }

        $parsedBody = $this->parser->parse([
            "errors" => [$exception->getMessage()]
        ], $format);

        return new Response(400, [
            'Content-Type' => $format
        ], $parsedBody);
    }
}
