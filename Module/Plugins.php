<?php

namespace LwSystemInfo\Module;

class Plugins
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
        $this->arr = array();

        if ($this->existsConfigPathPlugins()) {
            $this->checkExistingPlugins($this->config["path"]["plugins"]);
        }

        foreach ($this->config["plugin_path"] as $dir) {
            $this->checkExistingPlugins($dir);
        }

        return $this->arr;
    }

    public function existsConfigPathPlugins()
    {
        if (is_dir($this->config["path"]["plugins"])) {
            return true;
        }
        return false;
    }

    public function checkExistingPlugins($directoryPath)
    {
        if (substr($directoryPath, -1) == "/") {
            $temp = substr($directoryPath, 0, strlen($directoryPath) - 1);
            $explodedPath = explode("/", $temp);
        } else {
            $explodedPath = explode("/", $directoryPath);
        }
        $module = $explodedPath[count($explodedPath) - 1];

        $dir = \lw_directory::getInstance($directoryPath);
        $directories = $dir->getDirectoryContents("dir");

        if (!empty($directories)) {
            foreach ($directories as $directory) {

                $array[] = array(
                    "pluginname" => str_replace("/", "", $directory->getName())
                );
            }

            $this->arr[$module] = $array;
        }
    }

}
