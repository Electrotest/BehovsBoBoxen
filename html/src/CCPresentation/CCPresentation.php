<?php

/**
 * A user controller to manage content.
 *
 * @package BehovsboboxenCore
 */
class CCPresentation extends CObject implements IController {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Show a listing of all content.
     */
    public function Index() {
        $content = $this->Description();
        $this->views->SetTitle(t('Presentation Controller'))
                ->AddInclude(__DIR__ . '/index.tpl.php', array(

                    'header' => 'BehovsBoMetoden',
                    'presentation' => $content,
        ));
    }

    

    /**
     * Create new content.
     */
    public function Description() {
        $html ="";
        $html ="<br />";
        $html .= "<h3>BehovsBoMetoden gör hus smarta i nordiska klimat.</h3> 
        <br />
        BehovsBoMetoden är ett styrsystem för uppvärmningen av ditt hus.
        <h5>Varför</h5>
        Nordpool sätter idag elspotpriset per timma beroende på tillgång och efterfrågan. På vintern när 
        energibehovet är stort i samhället kan priset sticka iväg flera hundra procent. När alla duschar och 
        lagar mat ungefär samtidigt blir behovet av dyr toppkraft stor. Framöver när allt fler börjar köra 
        elbil blir det helt enkelt nödvändigt att samstyra laddning av elbil och drift av värmesystem. Då 
        kan plötsliga samtidiga uttag av el skada distributionsnäten. Alla vinner på att privata hushåll 
        hanterar effektuttaget, samhället (färre kraftverk), lokalsamhället (tryggare nät) och 
        privatpersoner (ekonomi).
        <br/><br/>
        <h5>Hur</h5> 
        Principen är att man utnyttjar en villas termiska tröghet. Man kan stänga av elen när det är dyrt (när det
        går åt mycket el) utan att påverka komforten i huset särskilt mycket. Istället kan man värma huset i förväg, 
        när elpriset är lägre. Man utnyttjar befintliga tillgångar som ackumulatortank, betongstomme, ett rum etc. 
        Golvvärme fungerar särskilt bra att styra.
        <br /><br />
        En Raspberry Pi enkortsdator med temperaturgivare mäter rumstemperaturen. 
        Morgondagens timspotpris hämtas automatiskt från Nordpools ftp server. Via ett webb-baserat användargränssnitt 
        ställer användaren in olika parametrar för att minimera kostnaderna för sin uppvärmning.
        <br /><br />
        <h5>Maker</h5>
        BehovsBoMetoden bygger på att all utrustning som ingår skall byggas med öppen källkod och öppen hårdvara 
        och att det finns en &quot;community&quot; som vidareutvecklar hård och mjukvara. Det finns många gamla 
        styrsystem runt om i Sverige som fungerar dåligt. En uppdatering till ett nytt kostar oftast 10000kr 
        och innehåller inte samma funktioner som BehovsBoBoxen ger. En händig villaägare kan själv bygga en 
        BehovsBoBox med hjälp av lite Youtube filmer som kommer att finnas på vår hemsida 
        <a href='http://www.behovsbo.se/?p=behovsboboxen'>www.behovsbo.se</a>.        
        <br /><br />
        Denna produkt är även ett intressant alternativ vid nybyggnad. BehovsBoBoxen är betydligt billigare 
        än alla etablerade alternativ, naturligt, eftersom man bygger ihop den själv. Den är också bättre eftersom den kan 
        göra fler saker. Den kan vara en normal termostat, sätta hela huset i &quot;bortaläge&quot;, den kan minimera 
        effektuttaget efter timpriset på el, samt logga mätvärden. 
        <br /><br />
        <h5>Framtidssäker</h5>
        Genom att BehovsBoBoxen är IP-baserad, och källkoden öppen, är systemet framtidssäkert. Det kan integreras 
        i den kommande världsstandarden på smarta hem - <a href='http://www.threadgroup.org/'>
        <span class='bold'>Thread</span></a>. Till exempel kan framtidens 
        trådlösa temperaturgivare, placerade i en tryckknapp, integreras i att styra rummets temperatur.
        <br /><br />
        <h5>Fler nyttor</h5>
        Det går att bredda och utveckla BehovsBoBoxen i det oändliga. Man kan sätta fuktsensorer i huset och ha koll
        på luftfuktighet, upptäcka vattenläckor etc. Man kan sätta sensorer i dörrkarmar och lås och upptäcka inbrott.
        Man kan styra lampor från olika tillverkare över samma trådlösa nätverk. Man kan ha trygghetslarm för äldre 
        kopplat till larmcentral.
        <br /><br />
        <h5>Materialet</h5>
        Du behöver detta:
                        <ul>
                    <li>1 Raspberry pi 2 modell B</li>
                    <li>7 ds18b20 med pinnar</li>
                    <li>1 ds18b20 vattensäker</li>
                    <li>1 micro SD kort 8GB</li>
                    <li>1 reläkort med 8 relän</li>
                    <li>1 5V 2,1A USB laddare</li>
                    <li>1 kopplingsdeck</li>
                    <li>1 knippe kopplingssladdar hane-hane</li>
                    <li>1 knippe kopplingssladdar hona-hona</li>
                    <li>1 Ethernetsladd</li>

                </ul>
        När man laddat ner och installerat Raspian 
        <br /><br />
        <a href='https://www.raspberrypi.org/downloads/noobs/'><code>https://www.raspberrypi.org/downloads/noobs/</code></a>
        <br /><br />
        klonar man BehovsBoBoxens katalog från github: 
        <br /><br />
        <a href='https://github.com/Electrotest/behovsboboxen'>
        <code>git clone https://github.com/Electrotest/BehovsBoBoxen</code></a>
        <br /><br /> 
        I README-filen får man mera information.
        <br /><br /><br />
        BehovsBoMetoden är skapad av Anders Kjellström.
        <br /><br />
        ";

        return $html;
    }

}



