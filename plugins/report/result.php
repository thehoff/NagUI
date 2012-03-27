<?php
require("header.inc.php");
  
switch($_GET['job'])
{
    case "details": 
        $item    = $_GET['item'];
        $was     = $_GET['was'];
        $title	 = $_GET['title'];
        
        
        $where   = stripslashes($_GET['where']);
        $where   = "$where AND $was = '$item' AND status != 'OK'";

        if($was == "host")
        {
            $spalte = "service";
        }else
        {
            $spalte = "host";
        }
        
        $filter = '';
        
        $assign = array(
			"item" 		=> $item,
			"title" 	=> $source,
			"source" 	=> $title,
			"items"		=> $report->getEvents($item, $filter),
        );
        
        $output->smartyDirect($assign,'plugins/report/dialog_details.html');
        

  break;
  
    case "statistik":        
        $text = $_GET['text'];
        $item  = $_GET['item'];
        $was   = $_GET['was'];   
        $where = stripslashes($_GET['where']);
        $where = "$where AND $was = '$item'";
        
        $query 	= 'SELECT COUNT(*) AS total_n FROM '.$table.' '.$where;
        $res 	= mysql_query($query,$db);
        $row		= mysql_fetch_row($res);
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
                            
                $query = "SELECT service, COUNT(*) AS n_events, ((COUNT(*) * 100.0) / $total_n) AS percentage FROM $table $where AND status != 'OK' GROUP BY service";
                

                $results 	= mysql_query($query,$db);
                $n 		= $total = $dtotal = 0;
                    
                    while($row = mysql_fetch_row($results))
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
                            
                $query = "SELECT host,service,status,beschreibung,DATE_FORMAT(zeit,'%d.%m.%Y %H:%i') AS zeit FROM $table $where ";

                $results 	= mysql_query($query,$db);
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