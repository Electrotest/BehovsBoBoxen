
#2015-07-28 10:14

sudo apt-get update -y
#Hämtar uppdateringar av Raspian

sudo apt-getupgrade -y
#Installerar uppdateringar av Raspian

sudo apt-get install vsftpd
#Hämtar ftp program

sudo apt-get install apache2 php5 libapache2-mod-php5 -y
#Hämtar webserver och php bibliotek

sudo apt-get install sqlite3
#Installerar databasen sqlite3

sudo apt-get install php5-sqlite
#Installerar koppling php-sqlite3

sudo a2enmod rewrite
#Aktiverar mod_rewrite

sudo service apache2 restart
#Startar om apache2

sudo --enable-ssl
sudo --enable-so
sudo make
sudo make install

sudo chmod 0666 /var/www/html -R
#Ändrar behörighet för mapp med filer till hemsidor

sudo chmod 0777 /var/www/html/application/data -R
#Full behörighet för datafilen

sudo chmod 0777 /var/www/html/application/textfiles -R
#Full behörighet för textfilesfilen

sudo chmod 777 /etc/rc.local
#Ändrar behörighet för script som körs vid reboot

sudo chmod 777 /etc/vsftpd.conf
#Ändrar behörighet för config fil för ftp

sudo chmod 777 /etc/network/interfaces
#Ändrar behörighet för nätverksinställningar

cp /home/pi/2015/rc.local /etc/rc.local
# kopierar filen som startar boxen vid reboot

cp /home/pi/2015/getspotprice.py /home/pi/getspotprice.py
#kopierar filen som hämtar spotpriset på Nordpool

cp /home/pi/2015/dallas2.py /home/pi/dallas2.py
#kopierar själva huvudfilen

cp -R /home/pi/2015/www /var/
#kopierar filerna för hemsidan
sudo chmod 0666 /var/www/html -R

rm /var/www/html/index.html

sudo chmod 777 /boot/config.txt
# ändrar behörighet
cp /home/pi/2015/config.txt /boot/config.txt

#skriv in text /boot/config.txt för att göra pinne 21 till dallas
#dtoverlay=w1-gpio,gpiopin=21 

#ändra port till 8080 /etc/apache2/ports.conf

sudo reboot
