<?php

namespace LwSystemInfo\Module;

class Packages
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
        $array = array();

        $dir = \lw_directory::getInstance($this->config["path"]["package"]);
        $directories = $dir->getDirectoryContents("dir");

        if (!empty($directories)) {
            foreach ($directories as $directory) {

                $array[] = array(
                    "packagename" => str_replace("/", "", $directory->getName())
                );
            }
        }

        return $array;
    }

}
