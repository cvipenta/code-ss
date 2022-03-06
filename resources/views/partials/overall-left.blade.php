<div id="leftColumn">

    <div class="boxleftColumn">
        <div style="overflow:hidden;">
            <a href="/analize-medicale.html">ANALIZE MEDICALE DE LABORATOR</a><br/>
            Aici gasiti analizele medicale grupate pe categorii precum si detalii generale si specifice pentru categoriile respective.<br/>
            Selectati o categorie din lista de mai jos:<br/>
            <ul style="list-type:none;">
                <li><a href="/analize-medicale/BIOCHIMIE.html">BIOCHIMIE</a></li>
                <li><a href="/analize-medicale/HEMATOLOGIE.html">HEMATOLOGIE</a></li>
                <li><a href="/analize-medicale/COAGULARE.html">COAGULARE</a></li>
                <li><a href="/analize-medicale/IMUNOLOGIE.html">IMUNOLOGIE</a></li>
                <li><a href="/analize-medicale/CITOLOGIE.html">CITOLOGIE</a></li>
                <li><a href="/analize-medicale/MICROBIOLOGIE.html">MICROBIOLOGIE</a></li>
                <li><a href="/analize-medicale/BIOLOGIE-MOLECULARA.html">BIOLOGIE MOLECULARA</a></li>
                <li><a href="/analize-medicale/ELECTROFOREZA.html">ELECTROFOREZA</a></li>
                <li><a href="/analize-medicale/URINA.html">URINA</a></li>
            </ul>
        </div>
    </div>


    <div class="boxleftColumn">

        <div id="left_dictionary">
            <p>Dictionar de medicamente online</p>
            <?
            foreach ($letters AS $l)
            {
                (($l == $_GET['letter']) && strstr($_SERVER['REQUEST_URI'], 'medicamente') == TRUE) ? $class = 'letter_selected' : $class = 'letter';
                echo "\t\t" . '<a href="' . SITE_URL . 'dictionar-medicamente/medicamente-care-incep-cu-' . $l . '.html" title="Litera ' . $l . ' - Dictionar medicamente">' . strtoupper($l) . '</a>' . "\n";
            }
            ?>
            <br/><br/>
            <p>Dictionar medical online</p>
            <?
            foreach ($letters AS $l)
            {
                (($l == $_GET['letter']) && strstr($_SERVER['REQUEST_URI'], 'medical') == TRUE) ? $class = 'letter_selected' : $class = 'letter';
                echo "\t\t" . '<a href="' . SITE_URL . 'dictionar-medical/termeni-medicali-' . $l . '.html" title="Litera ' . $l . ' - Dictionar medical">' . strtoupper($l) . '</a>' . "\n";
            }
            ?>

            <br/><br/>
            <span style="background-color:#ABCDEF; text-align:justify;">Puteti trimite articole cu tema medicala la <br/><a href="mailto:articole@startsanatate.ro">adresa de email</a></span>

        </div>
    </div>


    <div align="center" style="margin-top:20px; clear:both;">
        <!--/Start trafic.ro/-->
        <script type="text/javascript">t_rid = "startsanatate";</script>
        <script type="text/javascript" src="http://storage.trafic.ro/js/trafic.js"></script>
        <noscript><p><a href="http://www.trafic.ro/top/?rid=startsanatate"><img alt="trafic ranking" src="http://log.trafic.ro/cgi-bin/pl.dll?rid=startsanatate"/></a></p><a href="http://www.trafic.ro">Statistici web</a></noscript>
        <!--/End trafic.ro/-->
    </div>

</div>
