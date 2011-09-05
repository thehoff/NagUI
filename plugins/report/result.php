<?php
require("../kopf.inc.php");
require_once(dirname(__FILE__)."/../../module/Nagios/config.inc.php");
$sec->die_user_recht("Nagios","Zugriffsrechte");
  

  
switch($_POST['job'])
{
  case "beschreibung":
  
   $db_nag = db_connect($nag_config_db_host,$nag_config_db_user,$nag_config_db_pass);
   db_select_db($nag_config_db_name,$db_nag);

   db_query("SET NAMES utf8",$db_nag);
   db_query("SET CHARACTER SET utf8",$db_nag);
  
  
   $text = $_POST['text'];
   $item  = $_POST['item'];
   $was   = $_POST['was'];
   $where = stripslashes($_POST['where']);
   $where = "$where AND $was = '$item' AND status != 'OK'";
   

   if($was == "host")
   {
     $spalte = "service";
   }else
   {
     $spalte = "host";
   }
   echo '
 
  <div id="fenster_aussen">
  <div id="fenster_innen_big">
  <a href="#" onClick="window_close();"><img src="/t3img/32/close.png"></a>
   ';  
     
   echo "<h1>Details zu ".$_POST['item']."</h1>";
   
  echo $text;
	
	$query = "SELECT service,host,beschreibung, COUNT(*) AS n_events FROM $nag_config_db_table $where GROUP BY host, beschreibung ORDER BY n_events DESC";
	
	$results = db_query($query,$db_nag);
	$n = $tot_n_events = $dtot_n_events = 0;
	echo "<table class=\"default_result\">
	<tr><th>Eintrag</th><th>Fehler</th><th>Häufigkeit</th></tr>";

	while($row = db_fetch_row($results))
	{	 
	 echo "<tr><td>".$row[$spalte]."</td><td>".$row['beschreibung']."</td><td>". $row['n_events']."</td></tr>";
	 
	 $tot_n_events += $row['n_events'];
	 $dtot_n_events += $row['n_events'] * $row['n_events'];
	 $n++;
	}
	echo "<table>";
	$avg_n_events = $tot_n_events / $n;

	echo "Fehler im Durchschnitt ".round($avg_n_events,2);
  echo "</div></div>";

  break;
  
  case "statistik":
  
  $db_nag = db_connect($nag_config_db_host,$nag_config_db_user,$nag_config_db_pass);
  db_select_db($nag_config_db_name,$db_nag);

  db_query("SET NAMES utf8",$db_nag);
  db_query("SET CHARACTER SET utf8",$db_nag);
  
   $text = $_POST['text'];
   $item  = $_POST['item'];
   $was   = $_POST['was'];   
   $where = stripslashes($_POST['where']);
   $where = "$where AND $was = '$item'";
   
   $query 	= 'SELECT COUNT(*) AS total_n FROM '.$nag_config_db_table.' '.$where;
   $res 	= db_query($query,$db_nag);
   $row		= db_fetch_row($res);
   $total_n 	= $row['total_n'];


   echo '
    <div id="fenster_aussen">
    <div id="fenster_innen_big">
    <a href="#" onClick="window_close();"><img src="/t3img/32/close.png"></a>';
    echo "<h1>Statistik für $item </h1>";
    echo $text;
    
    if($was != 'service')
    {
    echo "<h2>Anzahl der Fehler je Service</h2>
      <table class=\"default_result\">
       <tr
         <th>Tag</th><th>Anzahl Events</th><th></th>";
                  
	 $query = "SELECT service, COUNT(*) AS n_events, ((COUNT(*) * 100.0) / $total_n) AS percentage FROM $nag_config_db_table $where AND status != 'OK' GROUP BY service";
	 

	$results 	= db_query($query,$db_nag);
	$n 		= $total = $dtotal = 0;
	
	while($row = db_fetch_row($results))
	{
	 echo "<tr>
	       <td>".$row['service']."</td>
	       <td>".$row['n_events']."</td>
	       <td>".round($row['percentage'],2)."%</td>
	      </tr>";
	      $n++;
	      $total+=$row['n_events'];
	      $dtotal+=$row['n_events'] * $row['n_events'];
        }
	$avg = $total / $n;
	
      echo "
      </table>
      Schnitt: ".round($avg,2);
      
     }
     
     echo "<h2>Zeiten</h2>
     
     <table class=\"default_result\">
       <tr>
         <th>Zeit</th>";
    	if($was != 'host')
        { 
          echo "<th>Host</th>";
        }
         
         echo "<th>Service</th><th>Status</th><th>Beschreibung</th>";
                  
	 $query = "SELECT host,service,status,beschreibung,DATE_FORMAT(zeit,'%d.%m.%Y %H:%i') AS zeit FROM $nag_config_db_table $where ";

	$results 	= db_query($query,$db_nag);
	$n 		= $total = $dtotal = 0;
	
	while($row = db_fetch_object($results))
	{
	 echo "<tr>
	 <td><small>$row->zeit </small> </b></td>";
	 if($was != 'host')
         {
	       echo "<td>$row->host </td>";
	 }      
	       echo "
	       <td>$row->service</td>";
	       switch($row->status)
	       {
	         case "OK":
	          $color = "green";
	         break;
	         
	         case "CRITICAL":
	          $color = "red";
	         break;
	         
	         case "WARNING":
	          $color = "yellow";
	         break;
	         
	         default:
	          $color = "white";
	         break;
	       }
	       
	       echo "
	       <td style=\"background-color: $color;\">$row->status </td>
	       <td>$row->beschreibung </td>
	       
	      </tr>";
	  
        }
	
      echo '
      </table>   
    </div>
   ';  
  break;
  
  case "close":
  break;

}


?>