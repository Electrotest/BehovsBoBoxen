#!/bin/bash
#2016-01-28 18.00

sudo apt-get update -y
#gets Raspian updates

sudo apt-get upgrade -y
#installs Raspian updates

sudo apt-get install vsftpd -y
#installs ftp programme

sudo apt-get install apache2 php5 libapache2-mod-php5 -y
#installs webserver and php library

sudo apt-get install sqlite3
#installs sqlite3

sudo apt-get install php5-sqlite
#installs php-sqlite

sudo a2enmod rewrite
#activates mod_rewrite

sudo service apache2 restart
#restarts apache2

sudo chmod 777 /home/pi/BehovsBoBoxen -R
#full file permissions for the the BehovsBoBoxen-repository from github

sudo cp /home/pi/BehovsBoBoxen/rc.local /etc/rc.local
#copies and change path for the file that starts the box at reboot

sudo cp /home/pi/BehovsBoBoxen/getspotprice.py /home/pi/getspotprice.py
#copies and change path for the file that gets the spotprice from Nordpool

sudo cp /home/pi/BehovsBoBoxen/dallas.py /home/pi/dallas.py
#copies and change path for the main file for BehovsBoBoxen

sudo chmod 777 /var/www/html -R
#full file permissions

sudo cp -R /home/pi/BehovsBoBoxen/html /var/www
#copies the files for the web-interface
sudo chmod 755 /var/www/html -R 
#restore file permissions

sudo chmod 777 /var/www/html/application/textfile -R
sudo chmod 777 /var/www/html/application/data -R
sudo chmod 777 /var/www/html/application/data/.ht.sqlite3
#full file permissions

echo "dtoverlay=w1-gpio,gpiopin=4" | sudo tee -a /boot/config.txt

sudo rm /var/www/html/index.html
#remove above file

sudo a2enmod ssl
sudo service apache2 restart
#enable mod_ssl 

sudo chmod 777 /etc/apache2 -R

sudo rm /etc/apache2/apache2.conf
sudo cp /home/pi/BehovsBoBoxen/apache2.conf /etc/apache2/apache2.conf
#AllowOverride All

#sudo mkdir /etc/apache2/ssl
sudo cp -rp /home/pi/BehovsBoBoxen/ssl /etc/apache2
sudo chmod 777 /etc/apache2/ssl -R
#adds ssl repository, to hold key and cerificate

sudo openssl req -x509 -nodes -days 1095 -newkey rsa:2048 -out /etc/apache2/ssl/behovsboboxen.crt -keyout /etc/apache2/ssl/behovsboboxen.key -subj "/C=SE/ST=Sverige/L=Molndal/O=/OU=/CN=behovsboboxen"

# req       request
#-nodes 	if a private key is created it will not be encrypted
#-newkey 	creates a new certificate request and a new private key
#rsa:2048 	generates an RSA key 2048 bits in size
#-keyout 	the filename to write the newly created private key to
#-out 		specifies the output filename
#-subj 		sets certificate subject
#x509		certificate display and signing utility
#-subj arg 	Replaces subject field of input request with specified data and outputs modified request. The arg must be formatted as /type0=value0/type1=value1/type2=..., characters may be escaped by \ (backslash), no spaces are skipped.


sudo rm /etc/apache2/sites-available/default-ssl.conf

sudo cp -rp /home/pi/BehovsBoBoxen/default-ssl /etc/apache2/sites-available/default-ssl
sudo cp -rp /home/pi/BehovsBoBoxen/000-default-ssl /etc/apache2/sites-enabled/000-default-ssl
#symlink text,  -p keeps file permissions from host to receiver

sudo cp -rp /home/pi/BehovsBoBoxen/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
sudo cp -rp /home/pi/BehovsBoBoxen/default-ssl.conf /etc/apache2/sites-enabled/default-ssl.conf
#443

sudo chmod 755 /etc/apache2
sudo chmod 755 /etc/apache2/ssl
sudo chmod 755 /home/pi/BehovsBoBoxen
#restores file permissions

sudo /etc/init.d/apache2 restart
#restarts apache2

sudo reboot