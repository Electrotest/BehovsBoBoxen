#!/usr/bin/python
# 2017-07-27 - modified by behovsboboxen
#Downloads spotprice.sdv and adds the file in /home/pi
import datetime
import time

from ftplib import FTP
ftp=FTP('ftp.nordpoolspot.com')
ftp.login('spot','spo1245t')
#ftp.retrlines('LIST')
ftp.retrbinary('RETR spotprice.sdv',open('spotprice.sdv','wb').write)

def file_get_contents(filename):
    with open(filename) as f:
        return f.read()

text = file_get_contents('spotprice.sdv')

tfile = "/home/pi/behovsboboxen/html/application/textfile/spotprice2.txt"
fhandle = open(tfile, "w")
fhandle.write(text)
fhandle.close()

actualtime = datetime.datetime.now()
testtext = 'Test: ' + str(actualtime)

testfile = "/home/pi/behovsboboxen/html/application/textfile/crontestingpy.txt"
fhandle = open(testfile, "w")
fhandle.write(testtext)
fhandle.close()

print(testtext)
