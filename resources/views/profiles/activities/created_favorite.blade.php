{{--@dump($activity->subject->favorited)--}}
@component('profiles.activities.activity')
    @slot('heading')
        <a href="/profiles/{{ $profileUser->name }}">{{ $profileUser->name }}</a> favorited: <a href="{{$activity->subject->favorited->path()}}">{{ $activity->subject->favorited->thread->title }}</a>
    @endslot
    @slot('body')
        <div class="body ">{{$activity->subject->favorited->body}}</div>
    @endslot
@endcomponent