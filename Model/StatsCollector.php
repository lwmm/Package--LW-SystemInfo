<?php

namespace LwSystemInfo\Model;

class StatsCollector
{

    protected $config;
    protected $db;

    public function __construct()
    {
        $this->config = \lw_registry::getInstance()->getEntry("config");
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }

    public function execute()
    {
        $array = array(
            'lw_cobject'   => 0,
            'lw_intranets' => 0,
            'lw_in_user'   => 0,
            'lw_master'    => 0,
            'lw_pages'     => 0,
            'lw_roles'     => 0,
            'lw_templates' => 0,
            'lw_user'      => 0,
            "lw_container" => 0,
            "lw_project_item" => 0,
            "lw_items"     => 0,
            "lw_itemtypes" => 0,
            "lw_comments"  => 0,
            "lw_types"     => 0
            );

        
        foreach($array as $tablename => $value){
            $this->db->setStatement("SELECT count(*) as amount FROM :tablename ");
            $this->db->bindParameter("tablename", "t", $tablename);
            $result = $this->db->pselect1();
            
            $array[$tablename] = $result['amount'];
        }

        return $array;
    }

}
