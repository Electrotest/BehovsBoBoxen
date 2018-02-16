#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
Downloads spotprice.sdv and adds the file in /home/pi
2017-08-06
"""
import datetime
#import time

from ftplib import FTP
SOURCE = FTP('ftp.nordpoolspot.com')
SOURCE.login('spot', 'spo1245t')
#SOURCE.retrlines('LIST')
SOURCE.retrbinary('RETR spotprice.sdv', open('spotprice.sdv', 'wb').write)

def file_get_contents(filename):
    """Fetches textfile"""
    with open(filename) as file:
        return file.read()

TEXT = file_get_contents('spotprice.sdv')

TFILE = "/home/pi/behovsboboxen/html/application/textfile/spotprice2.txt"
FHANDLE = open(TFILE, "w")
FHANDLE.write(TEXT)
FHANDLE.close()

ACTUALTIME = datetime.datetime.now()
TESTTEXT = 'Test: ' + str(ACTUALTIME)

TESTFILE = "/home/pi/behovsboboxen/html/application/textfile/crontestingpy.txt"
FHANDLE = open(TESTFILE, "w")
FHANDLE.write(TESTTEXT)
FHANDLE.close()

print(TESTTEXT)
