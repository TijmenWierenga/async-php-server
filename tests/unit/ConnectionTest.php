<?php
namespace TijmenWierenga\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TijmenWierenga\Server\Connection;

/**
 * @author Tijmen Wierenga <t.wierenga@live.nl>
 */
class ConnectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_instantiates_a_connection()
    {
    	$connection = Connection::init();

    	$this->assertEquals(Connection::DEFAULT_HOST, $connection->getHost());
    	$this->assertEquals(Connection::DEFAULT_PORT, $connection->getPort());

    	return $connection;
    }

    /**
     * @test
     * @depends it_instantiates_a_connection
     * @param Connection $connection
     */
    public function it_casts_a_connection_to_a_string(Connection $connection)
    {
        $this->assertEquals("{$connection->getHost()}:{$connection->getPort()}", $connection);
    }
}
