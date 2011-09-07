<?php

$source_type = (isset($_GET['source_type'])) ? $_GET['source_type'] : "livestatus";
$source = (isset($_GET['source'])) ? $_GET['source'] : "site1";

$assign['source_type'] = $source_type;
$assign['source'] = $source;

require("class/report.class.php");

 switch($source_type)
 {
    case "livestatus":
      require("class/livestatus_report.class.php");
      $report = new livestatus_report($source);
    break;
    
    
    case "db":
        
       //Datenbank Verbindung
       $db =  mysql_connect($cfg['report']['main']["db_host"],$cfg['report']['main']["db_user"], $cfg['report']['main']["db_pass"]);

       mysql_select_db($cfg['reportdb'][$source]['database'],$db);

       mysql_query("SET NAMES utf8",$db);
       mysql_query("SET CHARACTER SET utf8",$db);
       require("class/db_report.class.php");
       $report = new db_report($db,$cfg['reportdssssb'][$source]['table']);
     
    break;
    
 }



?>