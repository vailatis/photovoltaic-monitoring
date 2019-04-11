#!/usr/bin/python
# -*- coding: utf-8 -*-

#####################################################
### UPD listener to receive OWL Intuition packets ###
#####################################################

import time
import socket
import signal
import xmltodict
# apt-get -y install python-mysql.connector
# apt-get -y install python3-mysql.connector
import mysql.connector
from mysql.connector import errorcode

### OWL Network Listener settings ###
UDP_IP = "0.0.0.0"
UDP_PORT = 18000
UDP_KEY = "9E4F2FAE"

### MYSQL credentials ###
dbCONFIG = {
	'user'             : 'solar',
	'password'         : 'fiore12!',
	'host'             : '192.168.1.6',
	'database'         : 'solar_db',
	'raise_on_warnings': True,
}
#-----------------------------------------------------------------------------------------

### ELECTRICITY MESSAGE ###
#<electricity id='443719005025'>
#   <timestamp>1504042073</timestamp>
#   <signal rssi='-44' lqi='0'/>
#   <battery level='100%'/>
#   <chan id='0'>
#      <curr units='w'>402.00</curr>
#      <day units='wh'>15051.53</day>
#   </chan>
#   <chan id='1'>
#      <curr units='w'>0.00</curr>
#      <day units='wh'>0.00</day>
#   </chan>
#   <chan id='2'>
#      <curr units='w'>0.00</curr>
#      <day units='wh'>0.00</day>
#   </chan>
#</electricity>

### SOLAR MESSAGE ###
#<solar id='443719005025'>
#   <timestamp>1504042074</timestamp>
#   <current>
#      <generating units='w'>0.00</generating>
#      <exporting units='w'>0.00</exporting>
#   </current>
#   <day>
#      <generated units='wh'>0.00</generated>
#      <exported units='wh'>0.00</exported>
#   </day>
#</solar>

### WEATHER MESSAGE ###
#<weather id='443719005025' code='263'>
#   <temperature>19.00</temperature>
#   <text>Patchy light drizzle</text>
#</weather>


#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------

###################################################################################
### Function to intercept CTRL+C or SIGINT and precess shutdown code operations ###
###################################################################################
def SIGINT_Handler(signum, frame):
   global threadSTOP
   ### Change flag of weather data collector to stop its thread ###
   threadSTOP = 1
   print("Closing sockets...")

#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------


########################################################################
### Define a call-back function that intercept SIGINT/CTRL+C signals ###
########################################################################
threadSTOP = 0
signal.signal(signal.SIGINT, SIGINT_Handler)

###############################################
### Create the UDP socket and initialize it ###
###############################################
UDPsocket = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
UDPsocket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
UDPsocket.bind((UDP_IP, UDP_PORT))
 
###########################################
### Main loop waiting for received data ###
###########################################
dataENERGY = ""
while (threadSTOP == 0):
   ### Get received data from socket ###
   data, addr = UDPsocket.recvfrom(4096)
   rootTYPE = ""+data[:6]
   ### Analyze received data ###
   xmlDOC = xmltodict.parse(data)
   ### Display received data ###
   if (rootTYPE == "<elect"):
      dataENERGY = data
      xmlENERGY = xmltodict.parse(dataENERGY)
      dataENERGY = ""
      dataTIMESTAMP = time.strftime('%Y%m%d%H%M')
      ch0_curr = xmlENERGY["electricity"]["chan"][0]["curr"]["#text"]
      ch1_curr = xmlENERGY["electricity"]["chan"][1]["curr"]["#text"]
      ch2_curr = xmlENERGY["electricity"]["chan"][2]["curr"]["#text"]
      ch0_today = xmlENERGY["electricity"]["chan"][0]["day"]["#text"]
      ch1_today = xmlENERGY["electricity"]["chan"][1]["day"]["#text"]
      ch2_today = xmlENERGY["electricity"]["chan"][2]["day"]["#text"]
      battery = xmlENERGY["electricity"]["battery"]["@level"]
      signal = xmlENERGY["electricity"]["signal"]["@rssi"]
      ### Fix battery, by removing '%' sign ###
      battery = battery.replace("%", "")
      ### Insert data into database ###
      try:
         ### Connect to MySQL database ###
         myCONN = mysql.connector.connect(**dbCONFIG)
         ### Check if data is already present ###
         sqlQUERY = "SELECT COUNT(*) AS count FROM `owl_data` WHERE (timestamp = '"+dataTIMESTAMP+"')"
         myCURSOR = myCONN.cursor()
         myCURSOR.execute(sqlQUERY)
         resCOUNT = 0
         (resCOUNT,) = myCURSOR.fetchone()
         myCURSOR.close()
         if resCOUNT == 0:
            ### Display data to be inserted ###
            print "-----------------------------------------------"
            print "Timestamp...................: "+dataTIMESTAMP
            print "Electricity current...(Ch.0): "+ch0_curr+" Watt"
            print "Electricity current...(Ch.1): "+ch1_curr+" Watt"
            print "Electricity current...(Ch.2): "+ch2_curr+" Watt"
            print "Electricity today.....(Ch.0): "+ch0_today+" Wh"
            print "Electricity today.....(Ch.1): "+ch1_today+" Wh"
            print "Electricity today.....(Ch.3): "+ch2_today+" Wh"
            print "Battery.....................: "+battery+"%"
            print "Signal......................: "+signal+" dBm"
            print "-----------------------------------------------"
            ### Data missing, insert it ###
            sqlQUERY = ("INSERT INTO `owl_data` (`timestamp`,`ch0_curr`,`ch1_curr`,`ch2_curr`,"
                        "`ch0_today`,`ch1_today`,`ch2_today`,`battery`,`signal`)"
                        " VALUES "
                        "(%s,%s,%s,%s,%s,%s,%s,%s,%s)")
            sqlDATA = (dataTIMESTAMP, ch0_curr, ch1_curr, ch2_curr, ch0_today, ch1_today, ch2_today, battery, signal)
            myCURSOR = myCONN.cursor()
            myCURSOR.execute(sqlQUERY, sqlDATA)
            myCONN.commit()
            myCURSOR.close()
      except mysql.connector.Error as err:
         if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
            print("Something is wrong with your username or password")
         elif err.errno == errorcode.ER_BAD_DB_ERROR:
            print("Database does not exist")
         else:
            print(err)
      else:
         ### Close database connection
         myCONN.close()
      myCONN.close()


#################################
### Close the network sockets ###
#################################
UDPsocket.shutdown(socket.SHUT_RDWR)
UDPsocket.close()

#######################################
### Stop the main program execution ###
#######################################
print("Exit program.")
sys.exit()


## CREATE TABLE `owl_data` (
##   `timestamp` varchar(14) NOT NULL DEFAULT '',
##   `ch0_curr` decimal(6,2) NOT NULL DEFAULT '0.00',
##   `ch1_curr` decimal(6,2) NOT NULL DEFAULT '0.00',
##   `ch2_curr` decimal(6,2) NOT NULL DEFAULT '0.00',
##   `ch0_today` decimal(6,2) NOT NULL DEFAULT '0.00',
##   `ch1_today` decimal(6,2) NOT NULL DEFAULT '0.00',
##   `ch2_today` decimal(6,2) NOT NULL DEFAULT '0.00',
##   `battery` int(11) NOT NULL DEFAULT '0',
##   `signal` varchar(4) NOT NULL DEFAULT '',
##   `solar_curr_gener` decimal(6,2) NOT NULL DEFAULT '0.00',
##   `solar_curr_export` decimal(6,2) NOT NULL DEFAULT '0.00',
##   `solar_today_gener` decimal(6,2) NOT NULL DEFAULT '0.00',
##   `solar_today_export` decimal(6,2) NOT NULL DEFAULT '0.00',
##   PRIMARY KEY (`timestamp`),
##   KEY `timestamp_index` (`timestamp`));
## 
## CREATE INDEX timestamp_index ON owl_data (timestamp);
