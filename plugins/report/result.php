<?php
require("header.inc.php");

$item    	= $_GET['item'];
$grouping	= $_GET['was'];
$dialog_id	= $_GET['dialog_id'];


if(is_numeric($_GET['year']))
{
	$report->setByYear($_GET['year']);
}else
{
	$report->setByTimeperiod($_GET['time']);
}
if($_GET['soft'] != 'checked')
{
	$report->setNoSoftStats();
}

if(!isset($_GET['warning']))
{
	$report->setNoWarnings();
} 
  
switch($_GET['job'])
{
    case "details":   
        $assign = array(
			"item" 		=> $item,
			"source" 	=> $source,
			"items"		=> $report->getEvents($item, $grouping),
			"dialog_id" => $dialog_id,
        );
          
        $output->smartyDirect($assign,'plugins/report/dialog_details.html');
        

	break;
  
	case "stats":   
		 $assign = array(
			"item" 		=> $item,
			"source" 	=> $source,
			"items"		=> $report->getEvents($item, $grouping),
			"dialog_id" => $dialog_id,
			"context"	=> $grouping,
        );
            
		if($grouping == 'host')
		{
			$assign['host_items'] = $report->getHostStats($item);                
		}
        
        $assign['items'] = $report->getTimeline($item, $grouping);
                
/*
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
                        break;*/



		$output->smartyDirect($assign,'plugins/report/dialog_timeline.html');
    break;

}


?>