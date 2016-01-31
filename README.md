# Behovsboboxen - a controlsystem for smart homes based on Raspberry Pi.
BehovsBoBoxen är ett styrsystem för smarta hem baserad på Raspberry Pi. (Svensk text längre ner.)
![What you need](http://www.behovsbo.se/bilderipso/bbbmaterial.jpg)

Bom: | Get this
----- | --------

1 | 1 Raspberry pi 2 modell B 
2 | 7 ds18b20 with pins 
3 | 1 ds18b20 waterproof 
4 | 1 micro SD card 8GB 
5 | 1 relaycard med 8 relays 
6 | 1 5V 2,1A USB charger 
7 | 1 breadboard 
8 | 1 bunch connection-wires male-male 
9 | 1 bunch connection-wires female-female 
10 | 1 Ethernetwire 

1. Connect the temperature sensors (ds18b20) with plus, minus and signal on GPIO4.

2. Connect the relay card with it's 8 relays from the GPIO pins in the same order as described in the beginning of the file `/home/pi/dallas.py`

3. Normal mood for an ip-address is DHCP. If you wish a static ip-address, change in the file `/etc/network/interfaces`

4. In order to reach BehovsBoBoxen from internet you need to do a portforwarding in your router. Se your manual how it's done.

5. You have already downloaded and installed Raspian from `https://www.raspberrypi.org/downloads/noobs/`

6. Now, download the latest version from our repository using git client `git clone https://github.com/Electrotest/BehovsBoBoxen`

7. Run the installation script and follow the given instructions `sudo sh /home/pi/BehovsBoBoxen/install.sh`

8. Now you can enter your webpage with `https://your.ip.n.r` and log in with root:root. The first time you might get a warning and need to accept that you trust the certificate as it is signed by you and not a professional Certificate Authority, CA. 

9. You change username and password at the administrationpage. If you forget these you can install BehovsBoBoxen again. Not to much harm done for you.

10. If 1-wire sensors are plugged in according to the manual, you will find folders under `/sys/bus/w1/devices` where each sensor presents it's temperature.

11. The relays should be connected with dupont cabels according to the pin-configurations in `/home/pi/dallas.py`

12. Rundstyrning (Demand side management) can be used if you make an agreement with your utility company in order to controlle loads and prohibite disturbance on the local network.

13. You can change language to english if you open `/var/www/html/application/config.php` On line 100 you find `$bbb->config['language'] = 'sv_SE';` Change `'sv_SE to 'en_GB'`


# BehovsBoBoxen är ett styrsystem för smarta hem baserad på Raspberry Pi.

Materiallista:

* 1 Raspberry pi 2 modell B
* 7 ds18b20 med pinnar
* 1 ds18b20 vattensäker
* 1 micro SD kort 8GB
* 1 reläkort med 8 relän
* 1 5V 2,1A USB laddare
* 1 kopplingsdeck
* 1 knippe kopplingssladdar hane-hane
* 1 knippe kopplingssladdar hona-hona
* 1 Ethernetsladd

1. Koppla in temperaturgivarna (ds18b20) med plus, minus och signal på GPIO4

2. Reläkortet kopplas med sina 8 relän från GPIO pinnarna som är angivet i samma ordning som anges i början av filen `/home/pi/dallas.py`

3. Normal läge för IP adress är DHCP, om fast ip adress önskas ändrar man i filen `/etc/network/interfaces`

4. Om ni vill kunna nå BehovsBoBoxen från Internet måste ni göra en portvidarebefodran i er router, se er manual hur det görs.

5. Ni har laddat ner och installerat Raspian från `https://www.raspberrypi.org/downloads/noobs/`

6. Ladda nu ner den senaste versionen från vår katalog (repository) med git klienten `git clone https://github.com/Electrotest/BehovsBoBoxen`

7. Kör installationsskriptet och följ de givna instruktionerna `sudo sh /home/pi/BehovsBoBoxen/install.sh`

8. Nu kan du gå in på `https://your.ip.n.r` Logga in med root:root. Första gången kan du få en varning och få klicka för att du litar på certifikatet. Det är ju bara signerat av dig och inte någon professionell Certificate Authority, CA.

9. Du byter namn och lösenord på administrationssidan. Om du glömmer inloggningsuppgifterna kan du installera om Boxen  – ingen större skada skedd.

10. Om 1-wire sensorer är inkopplade enligt anvisningen, skall det med sökvägen `/sys/bus/w1/devices` finnas mappar där varje sensor anger sin temperatur.

11. Reläna skall kopplas med dupontsladdar enligt pin-konfigurationen i `/home/pi/dallas.py`

12. Rundstyrning kan användas om man gör ett avtal med sitt elnätsbolag om att styra laster för att förhindra störningar på det lokala elnätet. 

13. Du kan byta språk till engelska om du går till `/var/www/html/application/config.php` På rad 100 hittar du `$bbb->config['language'] = 'sv_SE';` Ändra `'sv_SE till 'en_GB'`


#### The interface of Behovsboboxen is based on Lydia, which is a PHP-based, MVC-inspired CMF

You find Lydia here: [Lydia](https://github.com/mosbth/lydia)

The modified and accustomed version is made by Gunvor Nilsson.


#### License

Behovsboboxen (and Lydia) is licensed according to MIT-license. 


[License/ Pricing:](http://canvasjs.com/download-html5-charting-graphing-library/)
> CanvasJS is free for non-commercial and paid for commercial use.