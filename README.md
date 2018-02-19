# BehovsBoBoxen är ett styrsystem för smarta hem baserad på Raspberry Pi.

![detta behöver du](http://www.behovsbo.se/themes/images/bbbmaterial.jpg)

Bom | Antal | Materiallista
--- | ------|--------------
1 | 1 | Raspberry pi 2 eller 3 modell B
2 | 9 | ds18b20 (1-wire eller dallas)
3 | 1 | micro SD kort 8GB
4 | 1 | reläkort med 8 relän
5 | 1 | 5V 2,1A USB laddare
6 | 1 | kopplingsdäck
7 | 1 | knippe kopplingssladdar hane-hane
8 | 1 | knippe kopplingssladdar hona-hona
9 | 1 | Ethernetsladd


1. Koppla in temperaturgivarna (ds18b20) med plus, minus och signal på GPIO4. Systemet är byggt för upp till 15 rum samt en utegivare. Om fler än 8 golvvärmeslingor ska styras krävs 2 reläkort.

2. Reläkortet kopplas med sina 8 relän från GPIO pinnarna som är angivet i samma ordning som anges i början av filen 
    `/home/pi/behovsboboxen/scripts/dallas.py`.    
    Om du har 2 kort, fortsätt koppla pinnarna enligt förlaga.

3. Tyvärr fungerar relä-korten lite olika. Det kan hända att du behöver ändra i `dallas.py`. Det märker du genom att allting fungerar tvärtom. I så fall ändra följande på rad 179 och 181:    
    True och False byter plats.

4. Normal läge för IP adress är DHCP. Man kan, om så önskas, låsa sin ip-adress via routern.

5. Om du vill kunna nå BehovsBoBoxen från Internet måste du göra en portvidarebefodran i er router, se manualen hur det görs. Ett exempel från nätet på hur man gör (april 2016) hittar du [här](https://www.modmypi.com/blog/how-to-give-your-raspberry-pi-a-static-ip-address-update).

6. Du har laddat ner och installerat Raspian från    
    `https://www.raspberrypi.org/downloads/noobs/`     
    och konfigurerat till svenska förhållanden.

7. Ladda nu ner den senaste versionen från vår katalog (repository) med git klienten     
    `git clone https://github.com/Electrotest/BehovsBoBoxen`

8. Kör installationsskriptet och följ de givna instruktionerna    
    `sudo sh /home/pi/BehovsBoBoxen/behovsboboxen/scripts/install.sh`

9. Nu kan du gå in på `https://your.ip.n.r` Logga in med root:root. Första gången kan du få en varning och få klicka för att du litar på certifikatet. Det är ju bara signerat av dig och inte någon professionell Certificate Authority, CA.
Boxen kan även hittas med adressen `https://behovsboboxen`. Vill du inte använda certifikatet utelämnar du s och skriver http.

10. Du byter namn och lösenord på administrationssidan. Om du glömmer inloggningsuppgifterna kan du installera om Boxen – ingen större skada skedd.

11. Om 1-wire sensorer är inkopplade enligt anvisningen, skall det med sökvägen `/sys/bus/w1/devices` finnas mappar där varje sensor anger sin temperatur. Öppna respektive mapp och öppna filen w1_slave. Där finns temperaturen, t.ex. t=21062. Dividera med 1000 så får du temperaturen i grader celcius.

12. Koppla upp givarna på kopplingsdäcket. Identifiera givarna genom att koppla dem till varje rum. Observera att den givare som har lägst id-nummer __skall__ sitta ute.

13. Reläna skall kopplas med dupontsladdar enligt pin-konfigurationen i    
    `/home/pi/behovsboboxen/scripts/dallas.py`.    
    Kom också ihåg att koppla 5 volt plus och gnd.

14. Rundstyrning (DSM) kan användas om man gör ett avtal med sitt elnätsbolag om att styra laster för att förhindra störningar på det lokala elnätet.


#### License

Behovsboboxen is licensed according to MIT-license. 


[License/ Pricing:](http://canvasjs.com/download-html5-charting-graphing-library/)
> CanvasJS is free for non-commercial and paid for commercial use.