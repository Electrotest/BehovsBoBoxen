
#2015-10-31

sudo apt-get update -y
#Hämtar uppdateringar av Raspian

sudo apt-getupgrade -y
#Installerar uppdateringar av Raspian

sudo apt-get install vsftpd
#Hämtar ftp program

sudo apt-get install apache2 php5 libapache2-mod-php5 -y
#Hämtar webserver och php bibliotek

sudo apt-get install sqlite
#Installerar databasen sqlite

sudo apt-get install php5-sqlite
#Installerar koppling php-sqlite

sudo a2enmod rewrite
#Aktiverar mod_rewrite

sudo service apache2 restart
#Startar om apache2

sudo --enable-ssl
sudo --enable-so
sudo make
sudo make install

sudo chmod 777 /var/www/html -R
#Ändrar behörighet för mapp med filer till hemsidor

sudo chmod 777 /etc/apache2/sites-available/000-default.conf 

sudo chmod 777 /etc/rc.local
#Ändrar behörighet för script som körs vid reboot

sudo chmod 777 /etc/vsftpd.conf
#Ändrar behörighet för config fil för ftp

sudo chmod 777 /etc/network/interfaces
#Ändrar behörighet för nätverksinställningar

cp /home/pi/behovsboboxen/rc.local /etc/rc.local
# kopierar filen som startar boxen vid reboot


cp /home/pi/behovsboboxen/dallas.py /home/pi/dallas.py
#kopierar själva huvudfilen

cp -R /home/pi/behovsboboxen/html /var/www
#kopierar filerna för hemsidan

sudo chmod 777 /var/www/html


cp /home/pi/behovsboboxen/config.txt /boot
# ändrar behörighet
cp /home/pi/behovsboboxen/config.txt /boot/config.txt

cp /home/pi/behovsboboxen/000-default.conf /etc/apache2/sites-available/

#skriv in text /boot/config.txt för att göra pinne 21 till dallas
#dtoverlay=w1-gpio,gpiopin=21 

#ändra port till 8080 /etc/apache2/ports.conf

sudo reboot
