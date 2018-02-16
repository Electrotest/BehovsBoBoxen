#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
Handling of temperature sensors in BehovsBoBoxen
2017-08-06
"""
import datetime
import time
import sqlite3

#import subprocess
import RPi.GPIO as GPIO

#GPIO general purpose input output
GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)
GPIO.setup(11, GPIO.OUT)
GPIO.setup(13, GPIO.OUT)
GPIO.setup(15, GPIO.OUT)
GPIO.setup(29, GPIO.OUT)
GPIO.setup(31, GPIO.OUT)
GPIO.setup(33, GPIO.OUT)
GPIO.setup(35, GPIO.OUT)
GPIO.setup(37, GPIO.OUT)
GPIO.setup(12, GPIO.OUT)
GPIO.setup(16, GPIO.OUT)
GPIO.setup(18, GPIO.OUT)
GPIO.setup(22, GPIO.OUT)
GPIO.setup(32, GPIO.OUT)
GPIO.setup(36, GPIO.OUT)
GPIO.setup(38, GPIO.OUT)
GPIO.setup(40, GPIO.OUT)

#master in raspberrypi ask these sensors (slaves)
SENSORS = [None] * 16

"""nr of rooms holding the setpoint values we want"""
SETPOINTS = [None] * 16

"""the value we have"""
ACTUALTEMP = [None] * 16

"""array of onoff values"""
ONOFF = [(None, None)] * 16

"""may be programmed for input or output"""
PINS = [11, 13, 15, 29, 31, 33, 35, 37, 12, 16, 18, 22, 32, 36, 38, 40]
#PINS = [13, 15, 11, 29, 31, 33, 35, 37, 12, 16, 18, 22, 32, 36, 38, 40]

def read_slaves():
    """read dallasgivare"""
    path1 = "/sys/bus/w1/devices/w1_bus_master1/w1_master_slaves"
    i = 0
    #number = get_nr_of_rooms()
    tfile = open(path1, 'r')
    for line in tfile:
        dallas = line[0:15]
        SENSORS[i] = dallas
        # print SENSORS[i], i
        i = i + 1
    tfile.close()

    #28-0000074f56d4 0
    #28-0000063a3f2c 1
    #28-0000074e73b2 2
    #28-000006dc96db 3

    #'28-0000063a3f2c', '28-0000074f56d4', '28-0000074e73b2', '28-000006dc96db'



def get_nr_of_rooms():
    """
    Opens the SQLite3 DB-file, fetches and returns nr of rooms
    """
    db_file = sqlite3.connect('/var/www/html/application/data/.ht.sqlite3')
    cursor = db_file.cursor()
    cursor.execute("SELECT nrofrooms FROM smallsettings;")
    all_rows = cursor.fetchall()

    for row in all_rows:
        """
        get chosen nr of rooms
        """
        nr_of_rooms = row[0] + 1

    db_file.close()
    return nr_of_rooms


def initiate(setp, actualtemp_array, sens):
    """
    Initiate readings by starting some functions
    """
    setpoints(setp)
    actualtemps(actualtemp_array, sens)
    update_tempfile(actualtemp_array)
    get_hand()
    #print "setpoints:", SETPOINTS
    #print "/actuals:", ACTUALTEMP
    #print "OnOff:", ONOFF


def setpoints(setp):
    """
    Opens sqlite3 file, extracts the various room's setpointvalues
    and then fills the list 'setpoints'
    """
    db_file = sqlite3.connect('/var/www/html/application/data/.ht.sqlite3')
    cursor = db_file.cursor()
    cursor.execute("SELECT * FROM roomsettings")
    all_rows = cursor.fetchall()
    index = 0

    for row in all_rows:
        """
        row[0] returns the first column in the query (rowid),
        row[2] returns setvalue.
        """
        setp[index] = row[2]
        index = index + 1

        db_file.close()
        return setp


def actualtemps(actualtemp_array, sens):
    """
    fills the list actualTemp with temperatures from
    the different room-sensors
    """
    basepath = "/sys/bus/w1/devices/"
    tailpath = "/w1_slave"
    number = get_nr_of_rooms()

    count = 0
    while count < number:
        string = str(sens[count])
        searchpath = basepath + string + tailpath
        tempfile = open(searchpath)
        text = tempfile.read()
        tempfile.close()
        secondline = text.split("\n")[1]
        temperaturedata = secondline.split(" ")[9]
        temperature = float(temperaturedata[2:])
        temperature = temperature / 1000
        actualtemp_array[count] = (round(temperature, 1))
        #print actualtemp_array[count]
        count = count + 1
    return actualtemp_array


def get_date():
    """
    read actual time and date, removes microseconds
    """
    date = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    return date


def update_tempfile(actualtemp_array):
    """
    write what happens here
    """
    file = open("/var/www/html/application/textfile/temperature.txt", "w")
    file.write('; '.join(map(str, actualtemp_array)))
    file.write("; ")
    file.write(get_date())
    file.close()


def update_sensors(relay):
    """Fetch chosen temperature for actual hour and control relay accordingly"""
    actualtime = datetime.datetime.now()
    actualhour = actualtime.hour
    room = "room" + str(relay)
    roomfile = "/var/www/html/application/textfile/" + room + ".txt"
    roomhandle = open(roomfile, "r")
    SETPOINTS[relay] = float(roomhandle.read().split(',')[actualhour])
    roomhandle.close()
    #end get setpoint
    if ACTUALTEMP[relay] > SETPOINTS[relay]:
        GPIO.output(PINS[relay], True)
    if ACTUALTEMP[relay] < SETPOINTS[relay]:
        GPIO.output(PINS[relay], False)


def turn_sensor_off(relay):
    """
    Turn the relay off
    """
    GPIO.output(PINS[relay], True)


def get_hand():
    """
    Get on off values for each room and populates OnOff array
    """
    db_file = sqlite3.connect('/var/www/html/application/data/.ht.sqlite3')
    cursor = db_file.cursor()
    cursor.execute("SELECT * FROM roomsettings;")
    all_rows = cursor.fetchall()
    i = 0
    start = 0
    off = 0

    for row in all_rows:
        """
        get if on or off
        """
        start = row[7]
        off = row[8]
        ONOFF[i] = (start, off)
        i = i + 1
    db_file.close()


def main():
    """
    Initiate lists with values and sets time and date
    """
    number = get_nr_of_rooms()
    read_slaves()

    while 1 > 0:
        initiate(SETPOINTS, ACTUALTEMP, SENSORS)

        """
        extracts setpoints for each room
        """
        relay = 0
        while relay < number:
            tup = ONOFF[relay]
            if tup[0] > 0 and tup[1] > 0:
                if datetime.datetime.now().hour >= tup[0] or datetime.datetime.now().hour < tup[1]:
                    update_sensors(relay)
                else:
                    turn_sensor_off(relay)
            else:
                update_sensors(relay)
            relay = relay + 1

        time.sleep(10)
        #vanta i 297s(ca 5min)

main()
