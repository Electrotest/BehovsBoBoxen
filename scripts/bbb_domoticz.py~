#!/usr/bin/python
#-*- coding: utf-8 -*-
#2016-03-14:21:51
#
# Modified by Ulf Bengtner, 2015-06-14, to read all sensors from Domoticz.
#
# All sensors are mapped by Domoticz sensor id, IDX. The actual sensors used
# are read from a configuration file /home/pi/domoticz_sensors. They should be
# listed in the same order as the room names are specified in Behovsbobox
#
#
import urllib2,json,sys
#import urllib2,json

import datetime;
import time;
import sqlite3
import datetime
import logging, logging.handlers, argparse
import subprocess
import RPi.GPIO as GPIO

#GPIO general purpose input output
GPIO.setmode(GPIO.BOARD)  # Board numbering mode, i.e. number refers to pin
GPIO.setwarnings(False)
#GPIO.setup(11,GPIO.OUT)
#GPIO.setup(13,GPIO.OUT)
#GPIO.setup(15,GPIO.OUT)
#GPIO.setup(29,GPIO.OUT)
#GPIO.setup(31,GPIO.OUT)
#GPIO.setup(33,GPIO.OUT)
#GPIO.setup(35,GPIO.OUT)
#GPIO.setup(37,GPIO.OUT)
#GPIO.setup(12,GPIO.OUT)
#GPIO.setup(16,GPIO.OUT)
#GPIO.setup(18,GPIO.OUT)
#GPIO.setup(22,GPIO.OUT)
#GPIO.setup(32,GPIO.OUT)
#GPIO.setup(36,GPIO.OUT)
#GPIO.setup(38,GPIO.OUT)
#GPIO.setup(40,GPIO.OUT)
#
# original pin-out Öland
#
GPIO.setup(26,GPIO.OUT) 
GPIO.setup(24,GPIO.OUT)
GPIO.setup(21,GPIO.OUT)
GPIO.setup(19,GPIO.OUT)
GPIO.setup(23,GPIO.OUT)
GPIO.setup(11,GPIO.OUT)
GPIO.setup(12,GPIO.OUT)
GPIO.setup(15,GPIO.OUT)


WAIT=300                              # Delay (s) between each cycle in control loop

DOMOTICZ_IN=1			      # Set this  variable to 1 to read temperatures from Domoticz, otherwise 0
DOMOTICZ_OUT=0                        # Set this  variable to 1 to control valves from Domoticz, otherwise 0

DOMOTICZ_URL="bobox.dyndns.org:8085"  # URL to Domoticz server - use 127.0.0.1 for local
USERNAME = 'ubee'                     # update username and password to what is defined in Domoticz
PASSWORD = 'xyz'

LOGFILE = '/home/pi/bbb_addon/bbb_domoticz.log'

def tracefunc(frame, event, arg, indent=[0]):
  calltrace=""
  if event == "call":
    indent[0] += 2
    logger.debug("-" * indent[0] + "> call function +%s",frame.f_code.co_name)
  elif event == "return":
    logger.debug("-" * indent[0] + "exit function +%s",frame.f_code.co_name)
    indent[0] -= 2
  return tracefunc
                      


#
# The control loop reads those sensors (slaves). The control loop stop
# when id is 0
#
# If Domoticz is used for control, the Domoticz idx is read from Domoticz. Otherwise configure with 1w id:s  
# 
#
# sensors[0] - first room
# sensors[1] - second room
# ...
#
sensors = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]

#
#sensors = ["28-00000662b340","28-000006636be0","28-00000663000e","28-00000558923e","28-000006636545","28-0000066355d5","28-00000663137f",0,0,0,0,0,0,0,0,0]
#
#
# array of rooms holding the setpoint values we want
#
setpoints = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
#
#
# the temperature value we read in each room
#
actualTemp = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
#
#
# The GPIO pin used to control the valve for each room
# With Domoticz control, the value is configured and read from Domoticz. The number corresponds to the IDX of the associated
# switch controlling a defined pin
#

pins = [26,24,21,19,23,11,12,15,0,0,0,0,0,0,0,0]

#
# define logging handler
#
parser = argparse.ArgumentParser( description='BehovsBoBoxen Control daemon' )
parser.add_argument("-v", "--verbose", help="Logging verbose", action="store_true")
                        
args = parser.parse_args()
if args.verbose:
  logging.basicConfig(level=logging.DEBUG)
else:
  logging.basicConfig(level=logging.INFO)
                            

logger = logging.getLogger(__name__)

# create a file handler

handler = logging.handlers.RotatingFileHandler(LOGFILE,maxBytes=200000,backupCount=10)
#
# set level 
#
handler.setLevel(logging.DEBUG)                    # Do not change this!

# create a logging format

formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
handler.setFormatter(formatter)

# add the handlers to the logger

logger.addHandler(handler)

logger.info("BehovsBoBoxen control system: bbb_domoticz.py starting up")

#
# Uncomment this line for extensive tracing
#
# sys.settrace(tracefunc)

if DOMOTICZ_IN or DOMOTICZ_OUT:
#
# build authenticate string to  access Domoticz server
#

  p = urllib2.HTTPPasswordMgrWithDefaultRealm()
  p.add_password(None, DOMOTICZ_URL, USERNAME, PASSWORD)

  handler = urllib2.HTTPBasicAuthHandler(p)
  opener = urllib2.build_opener(handler)
  urllib2.install_opener(opener)




  if DOMOTICZ_IN:
#
# Find all temperature sensors in Domoticz and populate sensors array
#
    url= "http://"+DOMOTICZ_URL+"/json.htm?type=devices&filter=temp&used=true&order=Name"
    logger.debug('Reading from %s',url)
    try:
      response=urllib2.urlopen(url)
      data=json.loads(response.read())
      logger.debug('Response is %s',json.dumps(data, indent=4, sort_keys=True))
      for i in range(len(data["result"])):
        a=data["result"][i]["Description"]
        ini=a.find('%room')
        if ini != -1:
           ini=ini+6
           rIndex=int(a[ini:])      
           logger.info('Configure room id %s with Domoticz sensor idx: %s', rIndex,  data["result"][i]["idx"])       
           sensors[rIndex]=data["result"][i]["idx"]
    except URLError:
      logger.warning('Cannot connect to Domoticz server %s', url)
      
#
# end if DOMOTICZ_IN
#

if DOMOTICZ_OUT:
#
# Find all swítches that control valves in Domoticz and populate output array
#
 
  url= "http://"+DOMOTICZ_URL+"/json.htm?type=devices&filter=light&used=true&order=Name"
  logger.debug('Reading from %s',url)
  response=urllib2.urlopen(url)
#  response=urllib.urlopen(url)  
  data=json.loads(response.read())
  logger.debug('Response is %s',json.dumps(data, indent=4, sort_keys=True))

  for i in range(len(data["result"])):
    a=data["result"][i]["Description"]
    ini=a.find('%room')
    if ini != -1:
       ini=ini+6
       rIndex=int(a[ini:])
       logger.info('Configure room id %s with Domoticz switch IDX %s',str(rIndex), data["result"][i]["idx"])

       pins[rIndex]=data["result"][i]["idx"]
#
# end if DOMOTICZ_OUT
#
logger.info('======================================================================')


#################################################################################
#
# actualTemps()
# 
# reads the temperatures in each room and returns array 
#
# depending on the variable domoticz the temperatures are read through Domoticz
# or directly from 1-wire sensors.
#
##################################################################################

def actualTemps(currentTemp,sensors):

	basePath="/sys/bus/w1/devices/"
	tailPath="/w1_slave"
        newTemp=[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
        readOK=True
	
	i = 0
	while i < 16 and sensors[i] != 0:

	    if DOMOTICZ_IN:
#
#             Read devices in Domoticz
#
              try:
                url= "http://"+DOMOTICZ_URL+"/json.htm?type=devices&rid=" + str(sensors[i]) 
                logger.debug('Reading from %s',url)
                response=urllib2.urlopen(url)                
                data=json.loads(response.read())
                logger.debug("Response is %s",json.dumps(data, indent=4, sort_keys=True))
              except:
#
#               no access to Domoticz server
#               return old reading
#
                readOK=False
                logger.warning("No access to Domoticz server")
                newTemp[i]=currentTemp[i]              
              if readOK :
                try:
                  temperature = data ["result"][0]["Temp"]
                  logger.debug('Room index %s, temperature is %s', i, temperature)
                  newTemp[i] = (round(temperature,1))
                except KeyError:
                  logger.warning("Domoticz sensor idx %s is not known!",sensors[i])  
            else:
#
#             Read 1 wire sensors the old way
#            
              searchPath = basePath + sensors[i] + tailPath
              logger.debug("Reading 1-wire from %s",searchPath)
              try:
                tfile = open(searchPath)
                text = tfile.read()
                tfile.close()
                secondline = text.split("\n")[1]
                temperaturedata = secondline.split(" ")[9]
                temperature = float(temperaturedata[2:])
                temperature = temperature / 1000
                newTemp[i] = (round(temperature,1))
              except IOError:
                logger.warning("Domoticz 1-wire %s is not known!",sensors[i])
                newTemp[i]=currentTemp[i]
              logger.debug('Room index %s, temperature is %s', i, newTemp[i])
            i = i + 1
        return newTemp
                                                                                                                         
######################################################################
#
# getdate()
#
# read actual time and date, removes microseconds
#
######################################################################

def getdate():
    date = datetime.datetime.now()
    return(unicode(date.replace(microsecond=0)))

######################################################################	
#
# updateTempFile()
#
# Write actual temperatures in file
#
######################################################################

def updateTempFile(actualTemp):
	file = open("/var/www/html/application/textfile/temperature.txt", "w") 
	file.write(getdate()) 
	file.write("; ") 
	file.write('; '.join(map(str, actualTemp)))	
	file.close

######################################################################
#
# set_output()
#
# controls the specified GPIO.pin either through Domoticz or directly
#
######################################################################

def set_output(id, pin, value):
    
    readOK=True
    
    logger.info('Room id %s, GPIO id %s, output %s',id, pin,value)
    if DOMOTICZ_OUT:
      try:        
        if value:
          url="http://"+DOMOTICZ_URL+"/json.htm?type=command&param=switchlight&idx="+str(pin)+"&switchcmd=Off"
        else:
          url="http://"+DOMOTICZ_URL+"/json.htm?type=command&param=switchlight&idx="+str(pin)+"&switchcmd=On"      
        logger.debug('Reading from %s',url)
        response=urllib2.urlopen(url)
      except:
        readOK =False
        logger.warning("No access to Domoticz server")
      if readOK:
        data=json.loads(response.read())
        logger.debug('Response is %s',json.dumps(data, indent=4, sort_keys=True))                                                                    
        if data ["status"] != "OK" :
          logger.warning("Domoticz switch idx %s is not known!",pin)                                  
    else:
      GPIO.output(pin,value)
      
    




##########################################################################
#
# the control loop
#
#########################################################################

def main():

    actualTemp = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
    while 1 > 0:            
#
#       read actual temperatures and store in temporay file to be displayed by web service
#
        actualTemp = actualTemps(actualTemp,sensors)
        updateTempFile(actualTemp)
                

	actualtime=datetime.datetime.now()   #get time
	actualhour=actualtime.hour           #get hour

	x = 0
	while x < 16 and sensors[x] != 0:
#
#               Get setpoints for room x from file
#
		room = "room" + str(x)
		string = "/var/www/html/application/textfile/" + room + ".txt"
                logger.debug('Reading setpoints from room %s from file %s', x, string)
		room = open(string, "r")
		setpoints[x]=float(room.read().split(',')[actualhour])
		room.close()
                logger.info('Room id %s, Temperature: %s, Setpoint: %s ', x, actualTemp[x], setpoints[x])	 

	    	if actualTemp[x] > setpoints[x]: set_output(x,pins[x],False)
	    	if actualTemp[x] < setpoints[x]: set_output(x,pins[x],True)
                logger.info('--------------------------------------------')
	        x = x + 1		

	time.sleep(WAIT)


try: 

  main()
  
except:
  logger.exception("bbb_domoticz.py terminated");