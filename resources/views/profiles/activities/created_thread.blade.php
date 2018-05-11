@component('profiles.activities.activity')
    @slot('heading')
        <a href="/profiles/{{ $profileUser->name }}">{{ $profileUser->name }}</a> published <a href="{{ $activity->subject->path() }}">{{$activity->subject->title}}</a>
    @endslot
    @slot('body')
        <div class="body ">{{$activity->subject->body}}</div>
    @endslot
@endcomponent