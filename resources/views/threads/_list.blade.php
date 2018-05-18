@forelse($threads as $thread)
    @include('threads._item')
@empty
    <p>There are no threads at this time</p>
@endforelse