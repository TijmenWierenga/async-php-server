<?php
namespace TijmenWierenga\Server;


interface Parser
{
    /**
     * Parses content to a given format
     *
     * @param mixed $content
     * @param string $format
     * @return mixed
     */
    public function parse($content, string $format);
}
