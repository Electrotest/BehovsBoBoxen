#!/usr/bin/python
#2017-08-15
#hamtar anslutan dallas givare och lagger id i textfil
import sqlite3
import urllib2
import urllib
from jsonrpclib import Server

#Change port and server address to your correct one
evokport=8026

s=Server("http://127.0.0.1:"+str(evokport)+"/rpc")

sensors=[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]

respons1=s.owbus_list(1)
#print respons1
respons2 = str(respons1)
respons3 = respons2.split("[")[2]
respons4 = respons3.split("]")[0]
senslist = respons4.split(", ")

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

def main():
    """"""
    nr = getNrOfRooms()
    print 'nrofrooms', nr

    sensor=0
    while sensor < nr:
        """"""
        #print "/var/www/html/application/textfile/sensor"+str(sensor)+".txt"
        file = open("/var/www/html/application/textfile/sensor"+str(sensor)+".txt", "w")
        sensors[sensor]=str(senslist[sensor])[2:18]
        #print 'str( sensors[sensor])', str( sensors[sensor])
        file.write(str( sensors[sensor]))
        file.close
        temp=s.sensor_get_value(sensors[sensor])
    
        print "Sensor",sensor,"=",sensors[sensor],round(temp,1),"C"
        sensor=sensor+1

main()

