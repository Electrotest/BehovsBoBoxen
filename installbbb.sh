#2015-11-06 8.47

sudo apt-get update -y
#Hämtar uppdateringar av Raspian
# sudo: Gives temporary root privileges.

sudo apt-get upgrade -y
#Installerar uppdateringar av Raspian

sudo apt-get install vsftpd -y
#Hämtar ftp program

sudo apt-get install apache2 php5 libapache2-mod-php5 -y
#Hämtar webserver och php bibliotek

sudo apt-get install sqlite3
#Installerar databasen sqlite3

sudo apt-get install php5-sqlite
#Installerar koppling php-sqlite

sudo a2enmod rewrite
#Aktiverar mod_rewrite

sudo service apache2 restart
#Startar om apache2

sudo chmod 0775 /var/www/html -R
#Ändrar behörighet för mapp med filer till hemsidor

sudo chmod 0777 /var/www/html/application/data -R
#Full behörighet för datakatalogen

sudo chmod 0777 /var/www/html/application/textfile -R
#Full behörighet för textfilekatalogen

sudo cp /home/pi/BehovsBoBoxen/rc.local /etc/rc.local
# kopierar filen som startar boxen vid reboot

#cp /home/pi/BehovsBoBoxen/dallas.py /home/pi/dallas.py
#kopierar själva huvudfilen

sudo cp /home/pi/BehovsBoBoxen/config/apache2/BehovsBoBoxen.conf /etc/apache2/sites-available
sudo a2ensite BehovsBoBoxen.conf
sudo a2dissite 000-default.conf
sudo service apache2 restart

sudo www-data cp -R /home/pi/BehovsBoBoxen/html /var/
#sudo -u www-data cp -R /home/pi/BehovsBoBoxen/html /var/
#kopierar filerna för hemsidan
# sudo cp -r -u -x -v [source directory] [destination directory]
# -u: Used with cp this switch causes cp to only copy files that have changed or don't already exist at the destination.

sudo chown -R www-data /var/www/html
# Sätter ownership till www-data på alla filer i /var/www/html

sudo usermod -a -G www-data pi
sudo usermod -a -G pi www-data
#lägger till användaren "pi" till gruppen "www-data" samt användaren "www-data" till gruppen "pi".
#Tack till https://github.com/maglub

echo "dtoverlay=w1-gpio,gpiopin=4" | sudo tee -a /boot/config.txt

sudo rm /var/www/html/index.html

sudo reboot