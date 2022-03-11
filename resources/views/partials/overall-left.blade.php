<div id="leftColumn">

    <div class="boxleftColumn">
        <div style="overflow:hidden;">
            <a href="/analize-medicale.html">{{__('ANALIZE MEDICALE DE LABORATOR')}}</a><br/>
            Aici gasiti analizele medicale grupate pe categorii precum si detalii generale si specifice pentru categoriile respective.<br/>
            Selectati o categorie din lista de mai jos:<br/>

            <x-medical-test-categories />
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
