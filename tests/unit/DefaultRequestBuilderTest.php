<?php
namespace TijmenWierenga\Tests\Unit;

use Nathanmac\Utilities\Parser\Parser;
use PHPUnit\Framework\TestCase;
use React\Http\ServerRequest;
use TijmenWierenga\Server\DefaultRequestBuilder;

/**
 * @author Tijmen Wierenga <t.wierenga@live.nl>
 */
class DefaultRequestBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_request_object_with_data_from_json()
    {
    	$builder = new DefaultRequestBuilder(new Parser());
    	$request = new ServerRequest("GET", "/", [
    	    "Content-Type" => "application/json"
        ]);

        $request = $builder->withData($request, json_encode([
            "name" => "Tijmen",
            "age" => 30
        ]));

        $this->assertEquals('Tijmen', $request->getParsedBody()['name']);
    }
}
