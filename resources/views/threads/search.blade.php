@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <ais-index
                    app-id="{{ config('scout.algolia.id') }}"
                    api-key="{{ config('scout.algolia.key') }}"
                    index-name="threads"
                    query="{{ request('q') }} "
            >
                <div class="col-md-8">
                    <ais-results>
                        <template slot-scope="{ result }">
                            <p>
                                <a :href="result.path">
                                    <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                                </a>
                            </p>
                        </template>
                    </ais-results>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Search
                        </div>
                        <div class="panel-body">

                            <div class="form-group">
                                <ais-search-box class="form-group">
                                    <ais-input placeholder="Search for something..." class="form-control"></ais-input>
                                </ais-search-box>

                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-default">Search</button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Filter
                        </div>
                        <div class="panel-body">
                            <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
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
            </ais-index>
        </div>
    </div>
@endsection
