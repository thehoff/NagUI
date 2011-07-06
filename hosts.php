<?php
# +-----------------------------------------------------------------+
# |                                                                 |
# |        (  ___ \     | \    /\|\     /||\     /|( (    /|        |
# |        | (   ) )    |  \  / /| )   ( || )   ( ||  \  ( |        |
# |        | (__/ /     |  (_/ / | |   | || (___) ||   \ | |        |
# |        |  __ (      |   _ (  | |   | ||  ___  || (\ \) |        |
# |        | (  \ \     |  ( \ \ | |   | || (   ) || | \   |        |
# |        | )___) )_   |  /  \ \| (___) || )   ( || )  \  |        |
# |        |/ \___/(_)  |_/    \/(_______)|/     \||/    )_)        |
# |                                                                 |
# | Copyright Bastian Kuhn 2011                mail@bastian-kuhn.de | 
# +-----------------------------------------------------------------+
#
# NagUI more information: http://nagui.de
#
# This is free software;  you can redistribute it and/or modify it
# under the  terms of the  GNU General Public License  as published by
# the Free Software Foundation in version 2.  NagUI is  distributed
# in the hope that it will be useful, but WITHOUT ANY WARRANTY;  with-
# out even the implied warranty of  MERCHANTABILITY  or  FITNESS FOR A
# PARTICULAR PURPOSE. See the  GNU General Public License for more de-
# ails.  You should have  received  a copy of the  GNU  General Public
# License along with GNU Make; see the file  COPYING.  If  not,  write
# to the Free Software Foundation, Inc., 51 Franklin St,  Fifth Floor,
# Boston, MA 02110-1301 USA.

require("./includes/header.inc.php");

$columns = (isset($_GET['columns'])) ? str_replace(","," ",$_GET['columns']) : "host_name state" ;
$colum_array = (isset($_GET['columns'])) ? explode(',',$_GET['columns']) : array("host_name","state");

$query = "GET hosts\nColumns: $columns\n";

$stats = "";
$status_count = 0;

foreach($_GET AS $index => $wert)
{
  switch($index)
  {
     case "filter_hostname":
      $query .= "Filter: host_name ~ $wert\nAnd: 1\n";
     break;
     
     case "filter_state_up":
      $status_count++;
      $stats .= "Filter: state = 0\n";
     break;
     
     case "filter_state_down":
      $status_count++;
      $stats .= "Filter: state = 1\n";
     break;
     
     case "filter_state_unreach":
      $status_count++;
      $stats .= "Filter: state = 2\n";
     break;
     
     case "filter_state_total":
      $query .= "Filter: state > 0\nFilter: scheduled_downtime_depth = 0\nAnd: 2\n";
     break;
     
     case "filter_state_unhandeld":
      $query .= "Filter: state > 0\nFilter: scheduled_downtime_depth = 0\nFilter: acknowledged = 0\nAnd: 3";
     break;
     
     case "filter_host_groups":
      $query .= "Filter: host_groups >= $wert\nAnd:1\n";
     break;
  }
}


//Status Filter setzen 
if($status_count != 0)
{
   $query .= $stats."Or: $status_count\n";
}

$erg_hosts = $livestatus->query($query);
$smarty->assign('fields', $livestatus->renderOutput($erg_hosts,$colum_array));
$smarty->display('hosts.html');

