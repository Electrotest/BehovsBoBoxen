Behovsboboxen - a controlsystem for smart homes based on Raspberry Pi.
======================================================================
BehovsBoBoxen är ett styrsystem för smarta hem baserad på Raspberry Pi. (Svensk text längre ner.)

<img src="http://www.behovsbo.se/bilderipso/bbbmaterial.jpg" width="90" />
Bom:

* 1 Raspberry pi 2 modell B
* 7 ds18b20 with pins
* 1 ds18b20 waterproof
* 1 micro SD card 8GB
* 1 relaycard med 8 relays
* 1 5V 2,1A USB charger
* 1 breadboard
* 1 bunch connection-wires male-male
* 1 bunch connection-wires female-female
* 1 Ethernetwire

You have already downloaded and installed Raspian from

	https://www.raspberrypi.org/downloads/noobs/

Now, download the latest version from our repository using git client

	git clone https://github.com/Electrotest/BehovsBoBoxen

Run the installation script and follow the given instructions

	sudo sh /home/pi/BehovsBoBoxen/install.sh

Now you can enter your webpage with https://your.ip.n.r

if 1-wire sensors are plugged in according to the manual, you will find folders under 

	/sys/bus/w1/devices

where each sensor presents it's temperature.
The relays should be connected with dupont cabels according to the pin-configurations in 

	dallas.py


BehovsBoBoxen är ett styrsystem för smarta hem baserad på Raspberry Pi.
=======================================================================

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

Ni har laddat ner och installerat Raspian från 

	https://www.raspberrypi.org/downloads/noobs/

Ladda nu ner den senaste versionen från vår katalog (repository) med git klienten

	git clone https://github.com/Electrotest/BehovsBoBoxen

Kör installationsskriptet och följ de givna instruktionerna

	sudo sh /home/pi/BehovsBoBoxen/install.sh

Nu kan du gå in på https://your.ip.n.r

Om 1-wire sensorer är inkopplade enligt anvisningen, skall det med sökvägen 

	/sys/bus/w1/devices 

finnas mappar där varje sensor anger sin temperatur.
Reläna skall kopplas med dupontsladdar enligt pin-konfigurationen i 

	dallas.py


The interface of Behovsboboxen is based on Lydia, which is a PHP-based, MVC-inspired CMF
----------------------------------------------------------------------------------------

You find Lydia here: https://github.com/mosbth/lydia

The modified and accustomed version is made by Gunvor Nilsson.


License
-------

Behovsboboxen (and Lydia) is licensed according to MIT-license. 