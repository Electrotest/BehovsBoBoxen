#!/usr/bin/python
#2016-01-28 16:28
#Hämtar spotprice.sdv och lägger filen i /home/pi
from ftplib import FTP
ftp=FTP('ftp.nordpoolspot.com')
ftp.login('spot','spo1245t')
#ftp.retrlines('LIST')
ftp.retrbinary('RETR spotprice.sdv',open('spotprice.sdv','wb').write)