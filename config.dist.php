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


  $sockets['site1']['path'] = "127.0.0.1"; #unix://path or IP Address to Livestatus socket
  $sockets['site1']['port'] = "6557"; #Null if local Socket else Port number for socket
  $sockets['site1']['timeout'] = "5" ; #Connect Timeout
  $sockets['site1']['auth'] = False; #If TRUE use authorization else FALSE
  
  
//   $sockets['site_name2']['path'] = "127.0.0.1";
//   $sockets['site_name2']['port'] = "6557"; 
//   $sockets['site_name2']['timeout'] = "5" ;
//   $sockets['site_name2']['auth'] = False;

  //Default Template
  $template = "intern";
  
  //Foreach active Plugin:  $cfg['plugins']['PLUGINNAME'] = "Nav Name";
  $cfg['plugins']['query'] = "Query Browser";
  
  //IF using auth, Source for username. Normaly $_SERVER["REMOTE_USER"]
  $cfg['env_user']  = $_SERVER["REMOTE_USER"];
