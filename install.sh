#! /bin/sh
#
#
#
# install.sh
#
# Description: Installs BehovsBoBoxen
#
# Usage: install.sh {web|all} {x}
#
# web - update the web server part
# all - installs the complete system including update/upgrade of system, install of additional packages etc.
# x   - If a second argument is specified, Domoticz support is installed, i.e. bbb_domoticz.py will be the
#       control process.
#

if [ -z "$2" ]
then

  DOMOTICZ=1
  echo "No Domoticz support"
else
  DOMITICZ=0
  echo "Domoticz support"
fi


# Set true (0)  if support for Domoticz control is desired
# Will use bbb_add/bbb_domoticz.py as control system and install startup script
# rc.local will not modified
#
#



#
# Function updates the webb application
#
do_web()
{
        echo "Update BehovsBoBoxen webb application"
        sudo chmod 755 /var/www -R
#       full file permissions

        sudo cp -R /home/pi/BehovsBoBoxen/html /var/www
#       copies the files for the web-interface
        sudo chmod 755 /var/www/html -R
#       restore file permissions

        sudo chmod 777 /var/www/html/application/textfile -R
        sudo chmod 777 /var/www/html/application/data -R
        sudo chmod 777 /var/www/html/application/data/.ht.sqlite3
        sudo chmod 777 /var/www/html/src/CCSpotprices/CCSpotprices.php
#       full file permissions

        sudo rm -f /var/www/html/index.html
#       remove above file
        
        RETVAL="$?"
        return "$RETVAL"
}

#
# Function that installs the whole BehovsBoBoxen
#
do_all()
{
        echo "Install BehovsBoBoxen"
        sudo apt-get update -y
#       gets Raspian updates

        sudo apt-get upgrade -y
#       installs Raspian updates

        sudo apt-get install vsftpd -y
#       installs ftp programme

        sudo apt-get install apache2 php5 libapache2-mod-php5 -y
#       installs webserver and php library

        sudo apt-get install sqlite3
#       installs sqlite3

        sudo apt-get install php5-sqlite
#       installs php-sqlite

        sudo a2enmod rewrite
#       activates mod_rewrite

        sudo service apache2 restart
#       restarts apache2

        sudo rm -f /var/www/html -R

        sudo chmod 777 /home/pi/BehovsBoBoxen -R
#       full file permissions for the the BehovsBoBoxen-repository from github

        if [$DOMOTICZ]
        then
            echo "Install Domoticz support"
            sudo cp /home/pi/BehovsBoBoxen/bbb_addon/bbb_domoticz.sh /etc/init.d
            sudo chmod 755 /etc/init.d/bbb_domoticz.sh
            sudo update-rc.d bbb_domoticz.sh defaults
        
        else
            sudo cp /home/pi/BehovsBoBoxen/scripts/rc.local /etc/rc.local
            sudo chmod 755 /etc/rc.local
#           copies and change path for the file that starts the box at reboot
        fi

        do_web()

        sudo crontab -l -u root |  cat /home/pi/BehovsBoBoxen/scripts/cron.txt | sudo c
#       we get the new spotpricefile after 16:00 and recalculate the temperatures afte


        cat /boot/config.txt |grep "dtoverlay=w1-gpio,gpiopin=4" ||  sudo tee -a /boot/
#       append "dtoverlay=..." to /boot/config.txt unless it's already there
#

        sudo a2enmod ssl
        sudo service apache2 restart
#       enable mod_ssl

        sudo chmod 777 /etc/apache2 -R

        sudo rm -f /etc/apache2/apache2.conf
        sudo cp /home/pi/BehovsBoBoxen/scripts/apache2.conf /etc/apache2/apache2.conf
#       AllowOverride All

#       sudo mkdir /etc/apache2/ssl
        sudo cp -rp /home/pi/BehovsBoBoxen/ssl /etc/apache2
        sudo chmod 777 /etc/apache2/ssl -R
#       adds ssl repository, to hold key and cerificate

        sudo openssl req -x509 -nodes -days 1095 -newkey rsa:2048 -out /etc/apache2/ssl

# req       request
#-nodes         if a private key is created it will not be encrypted
#-newkey        creates a new certificate request and a new private key
#rsa:2048       generates an RSA key 2048 bits in size
#-keyout        the filename to write the newly created private key to
#-out           specifies the output filename
#-subj          sets certificate subject
#x509           certificate display and signing utility
#-subj arg      Replaces subject field of input request with specified data and

        sudo rm -f /etc/apache2/sites-available/default-ssl.conf

        sudo cp -rp /home/pi/BehovsBoBoxen/scripts/default-ssl /etc/apache2/sites-avail
        sudo cp -rp /home/pi/BehovsBoBoxen/scripts/000-default-ssl /etc/apache2/sites-e
#       symlink text,  -p keeps file permissions from host to receiver

        sudo cp -rp /home/pi/BehovsBoBoxen/scripts/default-ssl.conf /etc/apache2/sites-
        sudo cp -rp /home/pi/BehovsBoBoxen/scripts/default-ssl.conf /etc/apache2/sites-
#       443

        sudo chmod 755 /etc/apache2
        sudo chmod 755 /etc/apache2/ssl
        sudo chmod 755 /home/pi/BehovsBoBoxen
#       restores file permissions


        sudo /etc/init.d/apache2 restart
#       restarts apache2



        echo "installation ok, the system will restart"
        sudo reboot


            

        RETVAL="$?"
        return "$RETVAL"
}

case "$1" in
  web)
        do_web
        ;;
  all)
        do_all
        ;;
  

  *)
        echo "Usage: $SCRIPTNAME {web|all}"
        exit 3
        ;;
esac

:
