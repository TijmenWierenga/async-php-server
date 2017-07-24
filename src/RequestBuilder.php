<?php
namespace TijmenWierenga\Server;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Tijmen Wierenga <t.wierenga@live.nl>
 */
interface RequestBuilder
{
    public function withData(ServerRequestInterface $request, $data): ServerRequestInterface;
}
