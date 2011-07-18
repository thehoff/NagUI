<?php

$fields_service = "host_name,host_state,description,plugin_output,state,pnpgraph_present,next_check,last_check,acknowledged,comments_with_info";
$fields_services = "host_name,host_state,description,plugin_output,state,action_url,next_check,last_hard_state_change";
$fields_servicegroups = "name,alias";

$fields_host = "host_name,state,pnpgraph_present,next_check,last_check,acknowledged,comments_with_info";
$fields_hosts = "host_name,state,num_services_hard_crit,num_services_hard_ok,num_services_hard_unknown,num_services_hard_warn,num_services_pending,plugin_output";
$fields_hostgroups = "name,alias";


$output_format = "smarty";