#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
# Written by Bastian Kuhn

db_host = "localhost"
db_user = "root"
db_pass = "root"
db_db   = "nagios_digger"

pop3_server = "vm-omd"


clients = {
  'kunde1': {
    'db_table'  : "log",
    },
   'kunde2': {
    'pop3_user' : "mailuser2",
    'pop3_pass' : "mail",
    'db_table'  : "log_2",
    },
}
