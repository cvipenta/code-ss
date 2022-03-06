<!DOCTYPE html>
<html lang="ro">

<head>

    <meta charset="utf-8">

    <title>@yield('title')</title>

    <meta name="keywords" content="$OVERALL_KEYWORDS"/>
    <meta name="description" content="$OVERALL_DESCRIPTION"/>

    <meta name="Copyright" content="&copy; 2009 Start Sanatate"/>
    <meta name="reply-to" content="office@startsanatate.ro"/>

    <meta name="Rating" content="General"/>
    <meta name="Robots" content="index,follow"/>

    <meta name="revisit-after" content="1 day"/>
    <meta name="verify-v1" content="VWtvXznTKYznP7ijN1bx/5yftrpsjgEjZKm+azIRZI0="/>
    <meta name="y_key" content="23524f7116fe9fd4"/>

    <link rel="stylesheet" href="/css/app.css" type="text/css"/>
    <link rel="stylesheet" href="/css/reset.css" type="text/css"/>
    <link rel="stylesheet" href="/css/layout.css" type="text/css"/>
    <link rel="stylesheet" href="/css/type.css" type="text/css"/>
    <link rel="stylesheet" href="/css/thickbox.css" type="text/css"/>
    <link rel="stylesheet" href="/css/autosuggest_inquisitor.css" type="text/css"/>
    <!--[if IE]>
    <link rel="stylesheet" href="/css/ie.css" type="text/css"/>
    <![endif]-->

    <link rel="shortcut icon" href="/favicon.ico"/>

    <script type="text/javascript" src="{{ asset('resources/views/js/jquery-1.6.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('resources/views/js/jquery.cycle.all.latest.js') }}"></script>
    <script type="text/javascript" src="{{ asset('resources/views/js/jcarousellite.js') }}"></script>
    <script type="text/javascript" src="{{ asset('resources/views/js/jquery.corner.js') }}"></script>
    <script type="text/javascript" src="{{ asset('resources/views/js/ss_script.js') }}"></script>
    <script type="text/javascript" src="{{ asset('resources/views/js/bsn.AutoSuggest_c_2.0.js') }}"></script>

    <script type="text/javascript">

        $(".box_1").corner();

        $('.slideshow').cycle({
            fx: 'fade' // choose your transition type, ex: fade, scrollUp, shuffle, etc...
        });

        var refreshId = setInterval(function () {
            $('#last_rss_news').load('do-lastrssnews');
        }, 10000);
        console.log(refreshId);

        var host = "http://www.startsanatate.ro";

        function getURL(mode, page, params) {
            if (page == "")
                return host + (!mode ? "index.php" : "/") + (params && params != "" ? (params.substr(0, 1) != "#" ? "&" : "") + params : "");
            return host + (!mode ? "/index.php?page=" : "") + (mode ? "/" : "") + page + (mode ? ".html" : "") + (params && params != "" ? (params.substr(0, 1) != "#" ? "&" : "") + params : "");
        }


        function validateSearchFieldsPage(form) {
            //var form = window.document.search_form;

            if (form.q.value.length < 4) {
                alert("Va rugam sa introduceti o expresie mai lunga de 4 caractere!");
                return false;
            }

            if ((form.q.value == "cauta medicament") || (form.q.value == "cauta termen medical")) {
                alert("Va rugam sa introduceti termenii de cautat!");
                return false;
            }

            return true;
        }

    </script>

    <style type="text/css">
        body {
            background: #8C7F69 repeat fixed;
        }
    </style>

</head>

<body>


<div id="wrapper">

    <div id="header">
        <div style="text-align:center; float:left; padding:10px;"><a href="/"><img src="/img/logo.png" alt="StartSanatate"/></a></div>
        <div style="text-align:center; float:right; padding-right:10px;">
            <?php
            if (date("i") % 13 == 0)
            {
                echo 'Top banner';
            }
            else
            {
            ?>
            <a href="http://asociatiamame.com/index.php/proiecte/medicina/215-centrul-stelutelor" target="_blank" title="Asociatia M.A.M.E.">
                <img src="http://asociatiamame.com/images/banner/01_468_x_60_full_banner_MAME.gif" alt="Asociatia M.A.M.E." width="468" height="60" style="border: 0;">
            </a>
            <?php
            }
            ?>
        </div>
    </div>

    <div id="menu">
        <ul>
            <li><a href="/" class="current" title="prima pagina">prima pagina</a></li>
            <li><a href="/dictionar-medical.html" title="dictionar medical">dictionar medical</a></li>
            <li><a href="/dictionar-medicamente.html" title="dictionar / lista medicamente">dictionar medicamente</a></li>
            <li><a href="/articole-medicale.html" title="articole medicale">articole medicale</a></li>
            <li><a href="/articole-medicale-rss.html" title="articole medicale rss">articole rss</a></li>
            <li><a href="/contact.html" title="contact">contact</a></li>
        </ul>
    </div>

    <div style="clear:both"></div>

    @include('partials.overall-left')


    <div id="centerColumnLayout2_ads">
        #content
        @yield('content')
    </div>

    <div id="right_ads">
        #right_ads
    </div>

    <br style="clear:both;"/>

    @include('partials.footer')

    <br style="clear:both;"/>
    <div style="float:left; display:block; padding:0 10px; text-align:center;">@yield('title')</div>
    <br style="clear:both;"/>

</div>

</div>

</body>

</html>
