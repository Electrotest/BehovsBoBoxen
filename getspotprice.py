#!/usr/bin/python
#2016-01-28 16:28
#Downloads spotprice.sdv and adds the file in /home/pi
from ftplib import FTP
ftp=FTP('ftp.nordpoolspot.com')
ftp.login('spot','spo1245t')
#ftp.retrlines('LIST')
ftp.retrbinary('RETR spotprice.sdv',open('spotprice.sdv','wb').write)