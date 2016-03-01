<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

class ConfigNtpForm { 
    
    public $enabledValue;
    
    public $enabledXpath;
    
    public $servers;
    
    public $formName;
    
    public function __construct() {
	$this->servers = array();
        //foreach($results as $result) {
            //$servers[] = new ConfigNtpServerForm($result);
        //}
    }    
}
