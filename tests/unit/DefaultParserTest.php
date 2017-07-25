<?php
namespace TijmenWierenga\Tests\Unit;

use Nathanmac\Utilities\Parser\Parser;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Server\DefaultParser;

/**
 * @author Tijmen Wierenga <t.wierenga@live.nl>
 */
class DefaultParserTest extends TestCase
{
    /**
     * @test
     */
    public function it_parses_request_body_to_a_given_format()
    {
    	$parser = new DefaultParser(new Parser());
    	$body = json_encode([
    	    'name' => 'Tijmen',
            'age' => 30
        ]);

    	$parsedBody = $parser->parse($body, 'application/json');
    	$this->assertEquals('Tijmen', $parsedBody['name']);
    	$this->assertEquals(30, $parsedBody['age']);
    }
}
