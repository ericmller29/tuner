@extends('base')

@section('content')
    <div class="songs">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="media song">
                    <div class="media-left">
                        <a href="#">
                            <img src="https://i.ytimg.com/vi/bDoqw51G3Do/hqdefault.jpg" alt="The Bouncing Souls - Lean on me" class="media-object" width="120">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">The Bouncing Souls</h4>
                        Lean On Sheena
                        <div class="btn-group btn-group-xs" role="group">
                            <a href="#" class="btn btn-primary">
                                <i class="glyphicon glyphicon-play"></i>
                                Play
                            </a>
                            <a href="#" class="btn btn-danger">
                                <i class="glyphicon glyphicon-trash"></i>
                                Delete
                            </a>
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Add <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                Playlist
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                Playlist
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                Playlist
            </div>
        </div>
    </div>
@stop