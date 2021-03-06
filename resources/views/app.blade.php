<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

        <title>Tuner</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,700,700i|Poppins:700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="https://use.fontawesome.com/3b7fb20a7d.js"></script>
    </head>
    <body>
        <div id="app" v-cloak>
            <div class="container" v-on:keyup.space="playPause">
                <header class="header">
                    <h1 class="logo">
                        <router-link to="/">t<span>u</span>ner</router-link>
                    </h1>
                    <div class="user">
                        @if(!Auth::check())
                        <button id="show-modal" @click="showLoginModal = true" class="btn margin-left">Login</button>
                        <button id="show-modal" @click="showRegisterModal = true" class="btn margin-left">Register</button>
                        @else
                        <button @click="mobileNavToggle = !mobileNavToggle" class="mobile-nav-toggle" v-bind:class="{ active: mobileNavToggle }"><i class="fa fa-bars"></i></button>
                        <nav class="user-nav">
                            <span>Welcome, {{ Auth::user()->username }}</span>
                            <a href="/settings" class="btn margin-left">Settings</a>
                            <a href="/app/logout" class="btn margin-left">Logout</a>
                            <img src="https://www.gravatar.com/avatar/{{ md5( strtolower( trim( Auth::user()->email ) ) ) }}?d={{ asset('img/no_avatar.svg') }}&s=40">
                        </nav>
                        @endif
                    </div>
                </header>
                <player></player>
            </div>
            <div class="container">
                <div class="content">
                    <search :searching="searching" :current-song="currentSong"></search>
                    <nav class="main-nav" v-if="!searching">
                        @if(Auth::check())
                        <router-link to="/" exact>My Library</router-link>
                        <router-link to="/playlists" exact>Playlists</router-link>
                        @if(Auth::user()->role == 69)
                        <router-link to="/admin" exact>Admin</router-link>
                        @endif
                        @else
                        <router-link to="/" exact>Popular Songs</router-link>
                        @endif
                    </nav>
                    <router-view :current-song="currentSong" v-if="!searching"></router-view>
                </div>
            </div>
            <modal v-if="showLoginModal" @close="showLoginModal = false">
                <h3 slot="header">Login</h3>
                <div slot="content">
                    <login></login>
                </div>
            </modal>
            <modal v-if="showRegisterModal" @close="showRegisterModal = false">
                <h3 slot="header">Register</h3>
                <div slot="content">
                    <register></register>
                </div>
            </modal>
        </div>

        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
