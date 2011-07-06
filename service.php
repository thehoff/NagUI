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
$columns = (isset($_GET['columns'])) ? str_replace(","," ",$_GET['columns']) : "host_name host_state description plugin_output state pnpgraph_present next_check last_check acknowledged comments_with_info" ;

$colum_array = (isset($_GET['columns'])) ? explode(',',$_GET['columns']) : explode(" ",$columns);

$host_name    = $_GET['host_name'];
$service_name = $_GET['service_name'];

$query = "GET services\nColumns: $columns\nFilter: host_name = $host_name\nFilter: description = $service_name\nAnd: 2";



$erg = $livestatus->query($query);

$smarty->assign('fields', $livestatus->renderOutput($erg,$colum_array));
$smarty->display('service.html');
