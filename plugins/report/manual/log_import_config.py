#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
# Written by Bastian Kuhn

db_host = "localhost"
db_user = "nagui"
db_pass = "j45wSsmhtJVuhm3T"
db_db   = "nagui_digger"

pop3_server = "vm-omd"


clients = {
  'source1': {
    'db_table'  : "log1",
    },
   'snmpsource': {
    'pop3_user' : "mailuser2",
    'pop3_pass' : "mail",
    'db_table'  : "log_2",
    },
}
