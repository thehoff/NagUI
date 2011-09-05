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

        $db_user = "root";
        $db_pass = "root";

       //Datenbank Verbindung
       $db =  mysql_connect($cfg_reporting["db_host"],$cfg_reporting["db_user"], $cfg_reporting["db_pass"]);

       mysql_select_db($cfg_reporting_dbs[$source]['database'],$db);

       mysql_query("SET NAMES utf8",$db);
       mysql_query("SET CHARACTER SET utf8",$db);
       require("class/db_report.class.php");
       $report = new db_report($db,$cfg_reporting_dbs[$source]['table']);
     
    break;
    
 }



?>