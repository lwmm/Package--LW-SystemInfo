<?php

namespace LwSystemInfo\Module;

class HttpsCheck
{

    protected $GET;
    protected $config;

    public function __construct($GET)
    {
        $this->GET = $GET;
        $this->config = \lw_registry::getInstance()->getEntry("config");
    }

    public function execute()
    {
        if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on" || $_SERVER["HTTPS"] == 1)) {
            return array("https" => true);
        }
        return array("https" => false);
    }

}
