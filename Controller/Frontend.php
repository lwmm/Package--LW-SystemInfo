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
    protected $request;

    public function __construct()
    {
        $this->config = \lw_registry::getInstance()->getEntry("config");
        $this->request = \lw_registry::getInstance()->getEntry("request");
    }

    public function execute()
    {
        if ($this->config["systeminfo"]["active"] == 1 && $this->config["systeminfo"]["allowed_ip"] == $_SERVER["REMOTE_ADDR"]) {
            if ($this->request->getAlnum("cmd")) {
                $cmd = $this->request->getAlnum("cmd");
            } else {
                $cmd = "getModules";
            }
            $method = $cmd . "Action";
            if (method_exists($this, $method)) {
                return $this->$method();
            } else {
                die("command " . $method . " doesn't exist");
            }
        }
    }

    protected function getModulesAction()
    {
        $array = array();

        $packageCollector = new \LwSystemInfo\Model\PackageCollector();
        $pluginCollector = new \LwSystemInfo\Model\PluginCollector();

        $array["packages"] = $packageCollector->execute();
        $array["plugins"] = $pluginCollector->execute();

        die(json_encode($array));
    }

    protected function getStatsAction()
    {
        $statsCollector = new \LwSystemInfo\Model\StatsCollector();
        $array = $statsCollector->execute();
        
        die(json_encode($array));
    }
    
    protected function getMd5Action()
    {
        $array = array();
        
        $array["expectedMd5"] = $this->request->getAlnum("expectedMd5");
        $array["configPath"] = $this->request->getAlnum("configPath");
        $array["path"] = urldecode($this->request->getRaw("filePath"));
        $array["completePath"] = $this->config["path"][$array["configPath"]] . $array["path"];

        $md5 = new \LwSystemInfo\Model\getMd5();
        $array["recievedMd5"] = $md5->execute($array["completePath"]);
        
        die(json_encode($array));
    }

}
