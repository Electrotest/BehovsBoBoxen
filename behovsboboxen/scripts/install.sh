#!/bin/sh
#2017-01-22 18.00

sudo apt-get update -y
#gets Raspian updates

sudo apt-get upgrade -y
#installs Raspian updates

sudo apt-get install vsftpd -y
#installs ftp programme

sudo apt-get install mysql-server mysql-client -y

sudo apt-get install php libapache2-mod-php apache2 -y
#installs webserver and php library

sudo apt-get install php7.0-sqlite3
#installs sqlite3

sudo systemctl enable ssh
sudo systemctl start ssh

sudo a2enmod rewrite
#activates mod_rewrite

#sudo service apache2 restart
sudo systemctl restart apache2
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

sudo chmod -R 777 /etc/apache2

sudo cp /home/pi/behovsboboxen/scripts/behovsboboxen.conf /etc/apache2/sites-available/behovsboboxen.conf

sudo a2ensite behovsboboxen
sudo bash -c "echo -e 127.0.1.1'\t'behovsboboxen >> /etc/hosts"
#add (after rapberrypi) 127.0.1.1 behovsboboxen in /etc/hosts
sudo systemctl reload apache2
#enable our site behovsboboxen

sudo ln -s  /home/pi/behovsboboxen/html /var/www/html
#symlink from our source-code to the html-directory

sudo crontab -l -u root |  cat /home/pi/behovsboboxen/scripts/cron.txt | sudo crontab -u root -
#we get the new spotpricefile after 16:00 and recalculate the temperatures after 00:00

grep -q -F "dtoverlay=w1-gpio,gpiopin=4" /boot/config.txt || sudo bash -c "echo 'dtoverlay=w1-gpio,gpiopin=4' >> /boot/config.txt"

sudo sed -i '/<Directory \/var\/www/>\/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

sudo a2enmod ssl
#install mod_ssl and Listen 443 in /etc/apache2/ports.conf
sudo a2ensite default-ssl
# activate
sudo service apache2 reload
#restart

sudo chmod -R 755 /etc/apache2
sudo chmod -R 755 /home/pi/behovsboboxen
sudo chmod -R 777 /home/pi/behovsboboxen/html/application/data
sudo chmod -R 777 /home/pi/behovsboboxen/html/application/textfile
#restores file permissions

sudo systemctl restart apache2
#enable mod_ssl and restart apache

#https://www.modmypi.com/blog/how-to-give-your-raspberry-pi-a-static-ip-address-update

echo "installation ok, the system will restart" | sudo tee -a /boot/config.txt

sudo reboot
