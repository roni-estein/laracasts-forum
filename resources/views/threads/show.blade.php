@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                                {{$thread->title}}
                            </span>

                            @can('update', $thread)
                            <form action="{{ $thread->path() }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button type="submit" class="btn btn-link">Delete Thread</button>

                            </form>

                            @endcan
                        </div>

                    </div>

                    <div class="panel-body">
                        <div class="body ">{{$thread->body}}</div>
                    </div>
                </div>

                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{ $replies->links() }}
                @auth
                    <form method="post" action="{{ $thread->path() . '/replies'}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say?"
                                      rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn bt-primary">Post</button>
                    </form>
                @else
                    <div class="text-center">Please <a href="{{route('login')}}">Sign In</a> to participate</div>
                @endauth


            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="body ">This post was written
                            by {{ $thread->creator->name }} {{ $thread->created_at->diffForHumans() }} and
                            has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
