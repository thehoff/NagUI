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

require("config.php");

#Read Config Files from conf.d bin
$conf_dir_handle = openDir($conf_dir);
while($cfg_file = readDir($conf_dir_handle))
{
    if(strstr($cfg_file, ".cfg")) 
    {
       foreach(parse_ini_file($conf_dir.$cfg_file,TRUE) AS $key => $inhalt);
       {
          $namespace = explode("_",$key);
          $ns     = $namespace[0];
          if(isset($namespace[1]))
          {
            $ns_var = $namespace[1];
            $cfg[$ns][$ns_var] = $inhalt;
            unset($ns_var);
          }else
          {
             $cfg[$ns] = $inhalt;
          }
          unset($ns);
          unset($namespace);
       }
    } 
}

require("./class/livestatus.class.php");
require("./class/output.class.php");

$template = (isset($_GET['template'])) ? $_GET['template'] : $template;
require("./templates/$template/defaults.php");

$livestatus     = new livestatus();
$output         = new output($template);
