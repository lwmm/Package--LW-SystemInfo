<?php

/* * ************************************************************************
 *  Copyright notice
 *
 *  Copyright 2013 Logic Works GmbH
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *  
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *  
 * ************************************************************************* */

namespace LwSystemInfo\Controller;

class Frontend
{

    protected $config;

    public function __construct()
    {
        $this->config = \lw_registry::getInstance()->getEntry("config");
    }

    public function execute()
    {
        if ($this->config["systeminfo"]["active"] == 1 && $this->config["systeminfo"]["allowed_ip"] == $_SERVER["REMOTE_ADDR"]) {
            $array = array();
            
            $packageCollector = new \LwSystemInfo\Model\PackageCollector();
            $pluginCollector = new \LwSystemInfo\Model\PluginCollector();
            
            $array["packages"] = $packageCollector->execute();
            $array["plugins"] = $pluginCollector->execute();
            
            die(json_encode($array));
        }
    }

}
