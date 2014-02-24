<?php

namespace LwSystemInfo\Module;

class TableSearch
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
        if($this->GET["searchField"] != "name" && $this->GET["searchField"] != "id"){
            $this->db->setStatement("SELECT id,name," . $this->GET["searchField"] . " FROM :tablename WHERE " . $this->GET["searchField"] . " LIKE :sValue ");
        }else{
            $this->db->setStatement("SELECT id,name FROM :tablename WHERE " . $this->GET["searchField"] . " LIKE :sValue ");
        }
        $this->db->bindParameter("tablename", "t", $this->GET["tablename"]);
        $this->db->bindParameter("sValue", "s", "%" . $this->GET["search"] . "%");
        $result = $this->db->pselect();

        return $result;
    }

}
