<?php

namespace LwSystemInfo\Module;

class Listtool
{

    protected $GET;
    protected $config;
    protected $db;

    public function __construct($GET)
    {
        $this->GET = $GET;
        $this->config = \lw_registry::getInstance()->getEntry("config");
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }

    public function execute()
    {
        $array = array();
        $array["db_links"] = $this->getLinkEntriesCount();
        $array["db_files"] = $this->getFileEntriesCount();
        $array["db_files_with_file"] = $this->getFileEntriesWithFileCount();
        $array["dir_files"] = $this->getSavedFilesCount();
        $array["list_count"] = $this->getListCount();

        return $array;
    }

    protected function getFileEntriesCount()
    {
        $this->db->setStatement("SELECT COUNT(*) as amount FROM t:lw_master WHERE lw_object = :lw_object AND opt1bool != 1 ");
        $this->db->bindParameter("lw_object", "s", "lw_listtool2");

        $result = $this->db->pselect1();

        return $result["amount"];
    }

    protected function getLinkEntriesCount()
    {
        $this->db->setStatement("SELECT COUNT(*) as amount FROM t:lw_master WHERE lw_object = :lw_object AND opt1bool = 1 ");
        $this->db->bindParameter("lw_object", "s", "lw_listtool2");

        $result = $this->db->pselect1();

        return $result["amount"];
    }
    
    protected function getFileEntriesWithFileCount()
    {
        $this->db->setStatement("SELECT COUNT(*) as amount FROM t:lw_master WHERE lw_object = :lw_object AND opt1bool != 1 AND ( opt1file != '' OR opt1file IS NOT NULL ) ");
        $this->db->bindParameter("lw_object", "s", "lw_listtool2");

        $result = $this->db->pselect1();

        return $result["amount"];
    }

    protected function getSavedFilesCount()
    {
        $fileCount = 0;
        $listtoolFiles = scandir($this->config["path"]["resource"] . "listtool");
        unset($listtoolFiles[0]);
        unset($listtoolFiles[1]);

        foreach ($listtoolFiles as $filename) {
            if (substr($filename, -5) == ".file") {
                $fileCount++;
            }
        }

        return $fileCount;
    }

    protected function getListCount()
    {
        $this->db->setStatement("SELECT DISTINCT category_id FROM lw_master WHERE lw_object = :lw_object ");
        $this->db->bindParameter("lw_object", "s", "lw_listtool2");

        $result = $this->db->pselect();

        return count($result);
    }

}
