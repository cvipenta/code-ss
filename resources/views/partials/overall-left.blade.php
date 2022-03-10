<div id="leftColumn">

    <div class="boxleftColumn">
        <div style="overflow:hidden;">
            <a href="/analize-medicale.html">{{__('ANALIZE MEDICALE DE LABORATOR')}}</a><br/>
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

    @php
        $letters = range('A', 'Z');
        //(($l == $_GET['letter']) && strstr($_SERVER['REQUEST_URI'], 'medicamente') == TRUE) ? $class = 'letter_selected' : $class = 'letter'
        //                (($l == $_GET['letter']) && strstr($_SERVER['REQUEST_URI'], 'medical') == TRUE) ? $class = 'letter_selected' : $class = 'letter';
    @endphp

    <div class="boxleftColumn">

        <div id="left_dictionary">
            <p>Dictionar de medicamente online</p>
            @foreach ($letters AS $l)
                <a href="{{ env('SITE_URL') }}dictionar-medicamente/medicamente-care-incep-cu-{{ $l  }}.html"
                   title="Litera {{ $l  }} - Dictionar medicamente">{{ $l  }}</a>
            @endforeach
            <br/><br/>

            <p>Dictionar medical online</p>

             @foreach ($letters AS $l)
                <a href="{{ env('SITE_URL') }}dictionar-medical/termeni-medicali-{{ $l  }}.html"
                   title="Litera {{ $l  }} - Dictionar medical">{{ $l  }}</a>
            @endforeach

        </div>
    </div>

</div>
