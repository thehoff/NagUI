#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
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

import sys
import re
import MySQLdb
import argparse
import poplib

from log_import_config import *

#Initialisieren der Verfügbaren Argumente
parser = argparse.ArgumentParser(description='NAGIOS LOG IMPORTER')
group1 = parser.add_argument_group()
group1.add_argument('-c', '--client', nargs='+', help='Only for the given client')
group1.add_argument('--allclients', action='store_true', help='Import for all clients')
group1.add_argument('--listclients', action='store_true', help='List of all clients')
group2 = parser.add_argument_group()
group2.add_argument('-f', '--file', help='Select a file as Source' )
group2.add_argument('-p', '--pop', action='store_true', help='Select pop3 Server as Source')
parser.add_argument('-t', '--timestamp', type=int, help='Use your own unixtimestamp as newest timestamp ')


args = parser.parse_args()
params = vars(args)

# Ausgabe aller Konfigurierten Clients angefordert
if params['listclients'] == True:
  for line in clients.keys():
    print line
  sys.exit()

#Es wurde kein Client übergeben, auch nicht das alle genutzt werden sollen
if params['client'] == None and params['allclients'] != True:
  parser.print_help()
  sys.exit()
  
#Es sollen alle Clients benutzt werden
if params['allclients'] == True:
  client_job_list = clients.keys()
#Oder es wurden Clients übergeben
else:
  client_job_list = params['client']

#Zur Datenbank verbinden
db = MySQLdb.connect(db_host,db_user,db_pass,db_db)
cursor = db.cursor ()
  
#Schleife durch alle angegeben Clients
for client in client_job_list:
  #Angegebener Client kommt nicht in der Konfig vor
  if client not in clients:
    print "Client %s not in configuration" % client
    continue

  #Client Konfiguration in conf Variable übernehmen
  conf = clients[client]

  #Datenquelle ist ein POP3 Server
  if params['pop'] != None and params['pop'] != False:
    print 'pop %s '  % params['pop'] 
    M = poplib.POP3(pop3_server)
    M.user(conf['pop3_user'])
    M.pass_(conf['pop3_pass'])
    numMessages = len(M.list()[1])
    #Erstelle das POP3 Object zum auslesen
    obj = []
    for i in range(numMessages):
      #Lese zuerst nur den Header aus
      for header_lines in M.top(i+1,0)[1]:
        #Suche das Subject Log
        if header_lines == "Subject: LOG":
          #Wenn du es findest übernimm den kompletten Mail inhalt
          for message in M.retr(i+1)[1]:
            obj += message.split("\n")
          #Mail Inhalt ist übernommen. Lösche die Mail vom Server
          M.dele(i+1)
    M.quit()     

  #Datenquelle ist eine Datei
  if params['file'] != None and params['file'] != False:
    obj = open(params['file'],"r")

  #Neusten Timestamp ermitteln
  cursor.execute("SELECT MAX(UNIX_TIMESTAMP(zeit)) FROM %s" % conf['db_table'])
  last_entry = cursor.fetchone()

  #Wurde der neuste Timestamp per Parameter überschrieben
  if params['timestamp'] != None:
    last_entry = []
    last_entry += [params['timestamp']]
  else:
    try:
      last_entry[0]
    except IndexError:
      last_entry = ["0"]

  # RegEx vorkompailieren 
  # 0=unixts, 1=host, 2=service, 3=status, 4=status_type, 5=count, 6=description
  pattern = re.compile(r"^\[([0-9]+)\] SERVICE ALERT: ([^;]+);([^;]+);([^;]+);([^;]+);([^;]+);(.*)$")
  insert = 0
  old    = 0
  error  = 0

  #Zeilen der Potentiellen LOG durchgehen
  for line in obj:
    match = re.search(pattern,line)
    if match:
      aus = match.groups();
      
      if int(aus[0]) > last_entry[0]:
        sql = "INSERT INTO %s VALUES (FROM_UNIXTIME('%s'),'%s','%s','%s','%s','%s','%s')" % (conf['db_table'],aus[0],aus[1],aus[2],aus[3],aus[4],aus[5],aus[6])
        try:
          cursor.execute(sql)
          db.commit()
          insert = insert + 1
        except Exception, e:
          print e
          error = error+ 1
          db.rollback
      else:
        old = old + 1
        
  print "%s Done. %i new entries, %i to old, %i errors" % (client,insert,old,error)

  if params['file'] != None:
    obj.close()

db.close()
