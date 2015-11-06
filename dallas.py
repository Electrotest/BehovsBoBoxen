#!/usr/bin/python
#2015-11-06

import datetime;
import time;
import sqlite3
import datetime

import subprocess
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)
GPIO.setup(26,GPIO.OUT)
GPIO.setup(24,GPIO.OUT)
GPIO.setup(21,GPIO.OUT)
GPIO.setup(19,GPIO.OUT)
GPIO.setup(23,GPIO.OUT)
GPIO.setup(11,GPIO.OUT)
GPIO.setup(12,GPIO.OUT)
GPIO.setup(15,GPIO.OUT)

slaveArray = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
setpointArray = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
actualTemp = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]

#read dallasgivare
path1="/sys/bus/w1/devices/w1_bus_master1/w1_master_slaves"
i=0
tfile=open (path1,'r')

for line in tfile:
  dallas=line[0:15]
  slaveArray[i] = dallas
  #print slaveArray[i] 
  i=i+1
tfile.close()

def initiate(setpointArray, actualTemp, slaveArray):
	setpointArray = setPoints(setpointArray)
	actualTemp = actualTemps(actualTemp, slaveArray)
	updateTempFile(actualTemp)
	#print "setpoints:", setpointArray, "/actuals:", actualTemp

def setPoints(setpointArray):
	setpoint=0

	# Creates or opens a file called mydb with a SQLite3 DB
	db = sqlite3.connect('/home/pi/BehovsBoBoxen/html/application/data/.ht.sqlite')
	cursor = db.cursor()
	cursor.execute("SELECT * FROM roomsettings")
	all_rows = cursor.fetchall()
	i=0

	for row in all_rows:
		#row[0] returns the first column in the query (rowid), row[1] returns id column.
		setpointArray[i] = row[2]
		i = i +1
	db.close()
	return setpointArray


def actualTemps(actualTemp, slaveArray):
	basePath="/sys/bus/w1/devices/"
	tailPath="/w1_slave"
	
	i = 0
	while i < 8:
		string = str(slaveArray[i])
		searchPath = basePath + string + tailPath
		tfile = open(searchPath)
		text = tfile.read()
		tfile.close()
		secondline = text.split("\n")[1]
		temperaturedata = secondline.split(" ")[9]
		temperature = float(temperaturedata[2:])
		temperature = temperature / 1000
		actualTemp[i] = (round(temperature,1))		
		#print actualTemp[i]
		i = i + 1
	return actualTemp


#las in aktuell datum och tid, samt ta bort microsekunderna
def getdate():
    date = datetime.datetime.now()
    return(unicode(date.replace(microsecond=0)))
	
def updateTempFile(actualTemp):
	file = open("/home/pi/BehovsBoBoxen/html/application/textfile/temperature.txt", "w") 
	file.write(getdate()) 
	file.write("; ") 
	file.write('; '.join(map(str, actualTemp)))	
	file.close

def main():

    while 1 > 0:            
		initiate(setpointArray, actualTemp, slaveArray)
		actualtime=datetime.datetime.now()#get time
		actualhour=actualtime.hour#get hour

		#get setpoint 
		fileroom0 = open("/home/pi/BehovsBoBoxen/html/application/textfile/room0.txt", "r")
		setpoint0=float(fileroom0.read().split(',')[actualhour])
		fileroom0.close()
		#end get setpoint 
                if actualTemp[0] < setpoint0: GPIO.output(26,False)
                if actualTemp[0] > setpoint0: GPIO.output(26,True)
                
		#get setpoint 
		fileroom1 = open("/home/pi/BehovsBoBoxen/html/application/textfile/room1.txt", "r")
		setpoint1=float(fileroom1.read().split(',')[actualhour])
		fileroom1.close()
		#end get setpoint 
                if actualTemp[1] < setpoint1: GPIO.output(24,False)
                if actualTemp[1] > setpoint1: GPIO.output(24,True)
                

                #get setpoint 
		fileroom2 = open("/home/pi/BehovsBoBoxen/html/application/textfile/room2.txt", "r")
		setpoint2=float(fileroom2.read().split(',')[actualhour])
		fileroom2.close()
		#end get setpoint 
                if actualTemp[2] < setpoint2: GPIO.output(21,False)
                if actualTemp[2] > setpoint2: GPIO.output(21,True)
                
                #get setpoint 
		fileroom3 = open("/home/pi/BehovsBoBoxen/html/application/textfile/room3.txt", "r")
		setpoint3=float(fileroom3.read().split(',')[actualhour])
		fileroom3.close()
		#end get setpoint 
                if actualTemp[3] < setpoint3: GPIO.output(19,False)
                if actualTemp[3] > setpoint3: GPIO.output(19,True)

                #get setpoint 
		fileroom4 = open("/home/pi/BehovsBoBoxen/html/application/textfile/room4.txt", "r")
		setpoint4=float(fileroom4.read().split(',')[actualhour])
		fileroom4.close()
		#end get setpoint 
                if actualTemp[4] < setpoint4: GPIO.output(23,False)
                if actualTemp[4] > setpoint4: GPIO.output(23,True)
                
		#get setpoint 
		fileroom5 = open("/home/pi/BehovsBoBoxen/html/application/textfile/room5.txt", "r")
		setpoint5=float(fileroom5.read().split(',')[actualhour])
		fileroom5.close()
		#end get setpoint 
                if actualTemp[5] < setpoint5: GPIO.output(11,False)
                if actualTemp[5] > setpoint5: GPIO.output(11,True)
                
                #get setpoint 
		fileroom6 = open("/home/pi/BehovsBoBoxen/html/application/textfile/room6.txt", "r")
		setpoint6=float(fileroom6.read().split(',')[actualhour])
		fileroom6.close()
		#end get setpoint 
                if actualTemp[6] < setpoint6: GPIO.output(12,False)
                if actualTemp[6] > setpoint6: GPIO.output(12,True)

                #get setpoint 
		fileroom7 = open("/home/pi/BehovsBoBoxen/html/application/textfile/room7.txt", "r")
		setpoint7=float(fileroom7.read().split(',')[actualhour])
		fileroom7.close()
		#end get setpoint 
                if actualTemp[7] < setpoint7: GPIO.output(15,False)
                if actualTemp[7] > setpoint7: GPIO.output(15,True)
                

                
                
                
                
                
                print getdate(), 'Actual temp=',actualTemp[1], 'Setpoint=' ,setpoint0 
		time.sleep(10) #vanta i 10s(ca 5min)
main();



  