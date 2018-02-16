#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
Handling of temperature sensors in BehovsBoBoxen
2016-08-24
# python 2
"""
import datetime
import time
import sqlite3
import subprocess
from jsonrpclib import Server
import urllib
import urllib2

#Change to correct address below, perhaps add port
url="http://127.0.1.1"

s=Server("http://127.0.1.1/rpc")

""" master in raspberrypi ask these sensors (slaves) """
sensors = [None] * 16

""" nr of rooms holding the setpoint values we want """
setpoints = [None] * 16

"""the value we have"""
actualTemp = [None] * 16

"""array of onoff values"""
OnOff = [None] * 16

def initiate(setp, OnOff):
    """
    we fill the array (rooms) with actual setpointvalues for each room in the function setPoints
    """
    setPoints(setp)
    getHand(OnOff)
    #print "setpoints: ", setpoints, "/actuals:", sensors

def setPoints(setp):
    """
    COpens sqlite3 file, extracts the various room's setpointvalues
    and then fills the list 'setpoints'
    """
    db = sqlite3.connect('/var/www/html/application/data/.ht.sqlite3')
    cursor = db.cursor()
    cursor.execute("SELECT * FROM roomsettings")
    all_rows = cursor.fetchall()
    b = 0

    for row in all_rows:
        """
        row[0] returns the first column in the query (rowid),
        row[2] returns setvalue.
        """
        setp[b] = row[2]
        b = b + 1

    db.close()
    return setp

def getHand(OnOff):
    """
    Get on off values for each room and populates OnOff array
    """
    db = sqlite3.connect('/var/www/html/application/data/.ht.sqlite3')
    cursor = db.cursor()
    cursor.execute("SELECT * FROM roomsettings;")
    all_rows = cursor.fetchall()
    b = 0
    at = 0
    off = 0

    for row in all_rows:
        at = row[7]
        off = row[8]
        OnOff[b] = at, off
        b = b + 1
    db.close()


def getNrOfRooms():
    """
    Opens SQLite3 DB, 'smallsettings'
    """
    db = sqlite3.connect('/var/www/html/application/data/.ht.sqlite3')
    cursor = db.cursor()
    cursor.execute("SELECT nrofrooms FROM smallsettings;")
    all_rows = cursor.fetchall()

    for row in all_rows:
        """
        get chosen nr of rooms
        """
        nrOfRooms = row[0] + 1

    db.close()
    return nrOfRooms


def getdate():
    """
    read actual time and date, removes microseconds
    """
    date = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    return date


def updateTempFile(actualT):
    """
    we write all the measured temperatures in a textfile
    """
    file = open("/var/www/html/application/textfile/temperature.txt", "w")
    file.write('; '.join(map(str, actualT)))
    file.write("; ")
    file.write(getdate())
    file.close()

def updateSensors(item):
    """
    item is nr in loop
    We fetch the chosen setpoint for a room and for current hour
    Adds it to array 'setpoints'
    We also fetch name of sensor (for that room) and gets actual temp
    Adds to array 'sensors'
    Values gets compared
    Relay turns on or off accordingly
    """
    actualtime = datetime.datetime.now()
    actualhour = actualtime.hour
    roomfile = "/var/www/html/application/textfile/room" + str(item) + ".txt"
    roomhandle = open(roomfile, "r")
    setpoints[item] = float(roomhandle.read().split(',')[actualhour])
    roomhandle.close()

    file = open("/var/www/html/application/textfile/sensor" + str(item) + ".txt", "r")
    sensors[item] = s.sensor_get_value(str(file.read()))
    #print s.sensor_get_value(str(file.read())), file.read()
    sensors[item] = file.read()
    
    #print sensors[item]
    file.close

    #print 'Arvarde= ', sensors[item], 'Borvarde= ', setpoints[item]
    if sensors[item] > setpoints[item]:
        response = urllib2.urlopen("http://127.0.1.1/rest/relay/" + str(item + 1), urllib.urlencode({'value': '0'})).read()
    if sensors[item] < setpoints[item]:
        response = urllib2.urlopen("http://127.0.1.1/rest/relay/" + str(item + 1), urllib.urlencode({'value': '1'})).read()


def turnSensorOff(item):
    """
    Turn the relay off
    """
    response = urllib2.urlopen("http://127.0.1.1/rest/relay/" + str(item + 1), urllib.urlencode({'value': '0'})).read()


def main():
    """
    Initiate lists with values and sets time and date
    """
    nr = getNrOfRooms()

    while 1 > 0:
        initiate(setpoints, OnOff)

        """
        extracts setpoints for each room
        """
        relay = 0
        while relay < nr:
            """"""
            tumb = OnOff[relay]
            if tumb[0] > 0 and tumb[1] > 0:
                if (datetime.datetime.now().hour >= tumb[0] or datetime.datetime.now().hour < tumb[1]):
                    updateSensors(relay)    
                else:
                    turnSensorOff(relay)
            else:
                updateSensors(relay)
            relay = relay + 1
                
        updateTempFile(sensors)
        time.sleep(10) #vanta i 297s(ca 5min)
        #print getdate(), 'Actual temp=',temp1, 'Setpoint=' ,setpoints[1]

main()
