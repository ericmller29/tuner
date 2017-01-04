<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') | Tuner</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <div id="app">
            <live-search></live-search>
            <div class="container">
                <div class="main-content">
                    <div class="row">
                        <div class="col-xs-12">
                            <nav class="main-nav">
                                <a href="#" class="active">My Library</a>
                                <a href="#">My Playlists</a>
                            </nav>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
            <player></player>
        </div>

        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
