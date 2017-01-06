<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') | Tuner</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,700,700i|Poppins:700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="https://use.fontawesome.com/3b7fb20a7d.js"></script>
    </head>
    <body>
        <div id="app">
            <button id="show-modal" @click="showModal = true">Show Modal</button>
            <div class="container">
                <header class="header">
                    <h1 class="logo">
                        t<span>u</span>ner
                    </h1>
                    <div class="user">
                        <span>Welcome, Eric Miller</span>
                        <img src="https://pbs.twimg.com/profile_images/754458799457595392/MocF2-kH.jpg">
                    </div>
                </header>
                <player></player>
            </div>
            @yield('content')
            <modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">Login</h3>
                <div slot="content">
                    Login to our lovely service.
                </div>
            </modal>
        </div>

        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
