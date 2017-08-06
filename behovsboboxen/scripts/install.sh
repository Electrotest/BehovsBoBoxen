#!/bin/sh
#2017-01-22 18.00

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

sudo chmod 777 /etc/rc.local
sudo rm -f /etc/rc.local

sudo chmod 777 /home/pi/BehovsBoBoxen/behovsboboxen -R
#full file permissions for the the BehovsBoBoxen-repository from github

sudo cp -R /home/pi/BehovsBoBoxen/behovsboboxen /home/pi/

sudo cp /home/pi/behovsboboxen/scripts/rc.local /etc/rc.local
#copies and change path for the file that starts the box at reboot
sudo chmod 755 /etc/rc.local

sudo rm -rf /var/www/html
sudo rm -rf /home/pi/BehovsBoBoxen

sudo cp /home/pi/behovsboboxen/scripts/behovsboboxen.conf /etc/apache2/sites-available/behovsboboxen.conf

sudo a2ensite behovsboboxen
sudo echo -e 127.0.1.1$'\t'behovsboboxen >> /etc/hosts
#add (after rapberrypi) 127.0.1.1 behovsboboxen in /etc/hosts
sudo service apache2 reload
#enable our site behovsboboxen

sudo ln -s  /home/pi/behovsboboxen/html /var/www/html
#symlink from our source-code to the html-directory

sudo crontab -l -u root |  cat /home/pi/behovsboboxen/scripts/cron.txt | sudo crontab -u root -
#we get the new spotpricefile after 16:00 and recalculate the temperatures after 00:00

#echo "dtoverlay=w1-gpio,gpiopin=4" | sudo tee -a /boot/config.txt
grep -q -F "dtoverlay=w1-gpio,gpiopin=4" /boot/config.txt || echo "dtoverlay=w1-gpio,gpiopin=4" >> /boot.config.txt

sudo chmod 777 /etc/apache2 -R

sudo rm -f /etc/apache2/apache2.conf
sudo cp /home/pi/behovsboboxen/scripts/apache2.conf /etc/apache2/apache2.conf
#AllowOverride All

sudo mkdir /etc/apache2/ssl
sudo chmod 777 /etc/apache2/ssl -R
#adds ssl repository, to hold key and cerificate

sudo openssl req -x509 -nodes -days 1095 -newkey rsa:2048 -out /etc/apache2/ssl/behovsboboxen.crt -keyout /etc/apache2/ssl/behovsboboxen.key -subj "/C=SE/ST=Sverige/L=/O=/OU=/CN=behovsboboxen"
# http://stackoverflow.com/questions/9224298/how-do-i-fix-certificate-errors-when-running-wget-on-an-https-url-in-cygwin
# req       request
#x509       certificate display and signing utility
#-nodes 	if a private key is created it will not be encrypted
#-days      valid 3 years (1095 days)
#-newkey 	creates a new certificate request and a new private key
#rsa:2048 	generates an RSA key 2048 bits in size
#-keyout 	the filename to write the newly created private key to
#-out 		specifies the output filename
#-subj 		sets certificate subject
#-subj arg 	Replaces subject field of input request with specified data and outputs modified request. The arg must be formatted as /type0=value0/type1=value1/type2=..., characters may be escaped by \ (backslash), no spaces are skipped.

sudo a2enmod ssl
#install mod_ssl and Listen 443 in /etc/apache2/ports.conf

#http://unix.stackexchange.com/questions/155150/where-in-apache-2-do-you-set-the-servername-directive-globally
sudo rm -f /etc/apache2/sites-available/default-ssl.conf
sudo cp -rp /home/pi/behovsboboxen/scripts/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
sudo ln -s /etc/apache2/sites-available/default-ssl.conf /etc/apache2/sites-enabled/000-default-ssl.conf

sudo chmod -R 744 /etc/apache2
sudo chmod -R 755 /home/pi/behovsboboxen
sudo chmod -R 777 /home/pi/behovsboboxen/html/application/data
sudo chmod -R 777 /home/pi/behovsboboxen/html/application/textfile
#restores file permissions

sudo service apache2 restart
#enable mod_ssl and restart apache

#https://www.modmypi.com/blog/how-to-give-your-raspberry-pi-a-static-ip-address-update

echo "installation ok, the system will restart" | sudo tee -a /boot/config.txt

sudo reboot