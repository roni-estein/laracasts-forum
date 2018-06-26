@extends('layouts.app')


@section('page-specific-css')
    <link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">

            <div class="row">
                <div class="col-md-8" v-cloak>
                    @include('threads._question')

                    <replies @removed="repliesCount--" @created="repliesCount++"></replies>

                </div>

                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="body ">This post was written
                                by {{ $thread->creator->name }} {{ $thread->created_at->diffForHumans() }} and
                                has @{{ repliesCount }} {{ str_plural('comment', $thread->replies_count) }}.
                            </div>
                            <p class="level">
                                <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                                <button class="btn btn-default" v-if="authorize('isAdmin')" v-text="locked ? 'Unlock' : 'Lock'" @click="toggleLock"></button>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </thread-view>
@endsection
