<?php
namespace TijmenWierenga\Server;

/**
 * @author Tijmen Wierenga <t.wierenga@live.nl>
 */
class Connection 
{
    const DEFAULT_PORT = 1337;
    const DEFAULT_HOST = '0.0.0.0';

    /**
     * @var string
     */
    private $port;
    /**
     * @var string
     */
    private $host;

    /**
     * Connection constructor.
     * @param string $port
     * @param string $host
     */
    private function __construct(string $port, string $host)
    {
        $this->port = $port;
        $this->host = $host;
    }

    /**
     * @param int $port
     * @param string $host
     * @return Connection
     */
    public static function init($port = self::DEFAULT_PORT, $host = self::DEFAULT_HOST): self
    {
        return new self($port, $host);
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    public function __toString(): string
    {
        return "{$this->host}:{$this->port}";
    }
}
