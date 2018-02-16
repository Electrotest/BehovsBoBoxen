#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
Logging tempvalues to textfile
2017-08-06
"""
import datetime
import time
import sqlite3

import subprocess
import RPi.GPIO as GPIO

"""GPIO general purpose input output"""
GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)
GPIO.setup(11,GPIO.OUT)
GPIO.setup(13,GPIO.OUT)
GPIO.setup(15,GPIO.OUT)
GPIO.setup(29,GPIO.OUT)
GPIO.setup(31,GPIO.OUT)
GPIO.setup(33,GPIO.OUT)
GPIO.setup(35,GPIO.OUT)
GPIO.setup(37,GPIO.OUT)
GPIO.setup(12,GPIO.OUT)
GPIO.setup(16,GPIO.OUT)
GPIO.setup(18,GPIO.OUT)
GPIO.setup(22,GPIO.OUT)
GPIO.setup(32,GPIO.OUT)
GPIO.setup(36,GPIO.OUT)
GPIO.setup(38,GPIO.OUT)
GPIO.setup(40,GPIO.OUT)

"""master in raspberrypi ask these sensors (slaves)"""
sensors = [None] * 16

"""the value we have"""
actualTemp = [None] * 16

"""may be programmed for input or output"""
pins = [11, 13, 15, 29, 31, 33, 35, 37, 12, 16, 18, 22, 32, 36, 38, 40]


def readSlaves():
    """read dallasgivare"""
    path1 = "/sys/bus/w1/devices/w1_bus_master1/w1_master_slaves"
    i = 0
    nr = getNrOfRooms()
    tfile = open(path1, 'r')
    for line in tfile:
        """fill sensors[i]"""
        dallas = line[0:15]
        sensors[i] = dallas
        # print sensors[i], i
        i = i + 1
    tfile.close()


def getNrOfRooms():
    """
    Opens the SQLite3 DB-file, fetches and returns nr of rooms
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


def initiate(actualT, sens):
    """
    Initiate readings by starting some functions
    """
    actualTemps(actualT, sens)
    logTempFile(actualT)
    print "/actuals:", actualTemp


def actualTemps(actualT, sens):
    """
    fills the list actualTemp with temperatures from 
    the different room-sensors
    """
    basePath = "/sys/bus/w1/devices/"
    tailPath = "/w1_slave"
    nr = getNrOfRooms()

    c = 0
    while c < nr:
        string = str(sens[c])
        searchPath = basePath + string + tailPath
        tempfile = open(searchPath)
        text = tempfile.read()
        tempfile.close()
        secondline = text.split("\n")[1]
        temperaturedata = secondline.split(" ")[9]
        temperature = float(temperaturedata[2:])
        temperature = temperature / 1000
        actualT[c] = (round(temperature, 1))
        print actualT[c]
        c = c + 1
    return actualT


def logTempFile(actualT):
    """
    write what happens here
    """
    file = open("/home/pi/behovsboboxen/scripts/templog.txt", "a")
    file.write('; '.join(map(str, actualT)))
    file.write("; ")
    file.write(getdate())
    file.close()

def getdate():
    """
    read actual time and date, removes microseconds
    """
    date = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    return date

def main():
    """
    Initiate lists with values and sets time and date
    """
    ute = 1
    nr = getNrOfRooms() - ute
    readSlaves()
    initiate(actualTemp, sensors)
main() 
