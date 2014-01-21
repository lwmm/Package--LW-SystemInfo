<?php

namespace LwSystemInfo\Model;

class PackageCollector
{

    protected $config;

    public function __construct()
    {
        $this->config = \lw_registry::getInstance()->getEntry("config");
    }

    public function execute()
    {
        $this->arr = array();
        
        $this->checkExistingPackages();
        
        return $this->arr;
    }

    public function checkExistingPackages()
    {
        $dir = \lw_directory::getInstance($this->config["path"]["package"]);
        $directories = $dir->getDirectoryContents("dir");

        if (!empty($directories)) {
            foreach ($directories as $directory) {
                
                $array[] = array(
                    "packagename" => str_replace("/", "", $directory->getName())
                );
            }
            
            $this->arr = $array;
        }
    }
}