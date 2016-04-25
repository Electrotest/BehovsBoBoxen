#!/bin/bash

# echo "Check processes and network connection"

. /lib/init/vars.sh
VERBOSE=""

. /lib/lsb/init-functions


DOMOTICZPID=$(</var/run/domoticz.pid)
DALLASPID=$(</var/run/dallas.pid)


ps p $DOMOTICZPID > /dev/null
if [  "$?" = "1" ] 
then
   echo $(date) "Domoticz is not running. Will be restarted"  | tee -a /var/log/checkp.log > /dev/null
  /etc/init.d/domoticz.sh start
fi


ps p $DALLASPID > /dev/null
if [ "$?" = "1" ]
then
   echo $(date) "Dallas.py is not running. Will be restarted" | tee -a  /var/log/checkp.log > /dev/null
   /etc/init.d/bbb_domoitcz.sh start
fi


ping -c 1 192.168.1.1 > /tmp/ping.log
if [ "$?" != "0" ]
then
#  date | tee -a /var/log/checkp.log > /dev/null
#  cat /tmp/ping.log | tee -a /var/log/checkp.log
  echo $(date) "No network connection. Will try to restart interface." | tee -a  /var/log/checkp.log > /dev/null
  /etc/init.d/networking stop
  /etc/init.d/networking start
#  ifdown wlan0&&ifup wlan0
  sleep 10s
  ping -c 1 192.168.1.1 > /tmp/ping.log
  if [ "$?" != 0 ]
  then
    echo $(date) "Network still down. Will reboot" | sudo tee -a /var/log/checkp.log > /dev/null
    sudo reboot
  fi  
  exit 0
fi