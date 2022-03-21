<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>

                <div class="py-12">
                    @if(session("success"))
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-1">
                            <div class="px-4 py-3 leading-normal text-green-700 bg-green-300 rounded-lg" role="alert">
                                {{session("success")}}
                            </div>
                        </div>
                    @endif

                    @if(session("error"))
                        <div class="max-w-7xl mx-auto sm:px-4 lg:px-8 my-1">
                            <div class="px-4 py-3 leading-normal text-red-100 bg-red-300 rounded-lg" role="alert">
                                <p>{{session("error")}}</p>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
