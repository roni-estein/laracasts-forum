@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
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

                    <replies @removed="repliesCount--" @created="repliesCount++"></replies>

                </div>

                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="body ">This post was written
                                by {{ $thread->creator->name }} {{ $thread->created_at->diffForHumans() }} and
                                has @{{ repliesCount }} {{ str_plural('comment', $thread->replies_count) }}.
                            </div>
                            <p>
                                <subscribe-button :active="{{ json_encode($thread->isSubscribedTo)  }}"></subscribe-button>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </thread-view>
@endsection
