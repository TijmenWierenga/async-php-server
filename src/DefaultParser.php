<?php
namespace TijmenWierenga\Server;

use Nathanmac\Utilities\Parser\Parser as ParserUtility;

/**
 * @author Tijmen Wierenga <t.wierenga@live.nl>
 */
class DefaultParser implements Parser
{
    /**
     * @var ParserUtility
     */
    private $utility;

    /**
     * DefaultParser constructor.
     * @param ParserUtility $utility
     */
    public function __construct(ParserUtility $utility)
    {
        $this->utility = $utility;
    }

    /**
     * Parses content to a given format
     *
     * @param mixed $content
     * @param string $format
     * @return mixed
     */
    public function parse($content, string $format)
    {
        $class = $this->utility->getFormatClass($format);
        $formatter = new $class;

        return $this->utility->parse($content, $formatter);
    }
}
