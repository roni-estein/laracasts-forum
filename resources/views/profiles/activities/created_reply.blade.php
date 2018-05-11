@component('profiles.activities.activity')
    @slot('heading')
        <a id="reply-{{ $activity->subject->id }}" href="/profiles/{{ $profileUser->name }}">{{ $profileUser->name }}</a> replied to: {{$activity->subject->thread->title}}
    @endslot
    @slot('body')
        <div class="body ">{{$activity->subject->body}}</div>
    @endslot
@endcomponent