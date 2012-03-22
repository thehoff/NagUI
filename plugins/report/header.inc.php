<?php

$source = (isset($_GET['source'])) ? $_GET['source'] : "No Source selected";


if(isset($plugin_cfg[$source]['type']))
{
   $assign['source_type'] = $plugin_cfg[$source]['type'];
   $assign['source'] = $source;
}

require("class/report.class.php");

 switch($plugin_cfg[$source]['type'])
 {
    case "livestatus":
      require("class/livestatus_report.class.php");
      $report = new livestatus_report($plugin_cfg[$source]['socket']);
    break;
    
    
    case "db":
        
       //Datenbank Verbindung
       $db =  mysql_connect($plugin_cfg['main_cfg']["db_host"],$plugin_cfg['main_cfg']["db_user"], $plugin_cfg['main_cfg']["db_pass"]);

       mysql_select_db($plugin_cfg[$source]['database'],$db);

       mysql_query("SET NAMES utf8",$db);
       mysql_query("SET CHARACTER SET utf8",$db);
       require("class/db_report.class.php");
       $table = $plugin_cfg[$source]['table'];
       $report = new db_report($db,$table);
     
    break;
    
    
    default:
       $report = new report();
    break;   
    
 }



?>