<?php
namespace TijmenWierenga\Server;

use Nathanmac\Utilities\Parser\Parser;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Tijmen Wierenga <t.wierenga@live.nl>
 */
class DefaultRequestBuilder implements RequestBuilder
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * DefaultRequestBuilder constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function withData(ServerRequestInterface $request, $data): ServerRequestInterface
    {
        $contentType = ($request->hasHeader('Content-Type')) ? $request->getHeader('Content-Type')[0]: null;
        $class = $this->parser->getFormatClass($contentType);
        $formatter = new $class;
        $data = $this->parser->parse($data, $formatter);

        return $request->withParsedBody($data);
    }
}
