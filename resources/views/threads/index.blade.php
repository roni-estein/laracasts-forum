@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')

                {{ $threads->render() }}
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form method="get" action="/threads/search">
                            <div class="form-group">
                                <input name="q" placeholder="Search for something..." class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-default">Search</button>
                            </div>

                        </form>
                    </div>
                </div>

                @if(count($trending))
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Trending Threads
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                @foreach($trending as $thread)
                                    <li class="list-group-item">
                                        <a href="{{ url($thread->path) }}">
                                            {{ $thread->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
