@extends('layouts.app')
@section('head')
    <style>
        .ais-highlight em { font-style: normal; background-color: yellow; font-weight: bold}
    </style>
@endsection

@section('content')

    <div class="container">



            <ais-search-box></ais-search-box>


            <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>

            <ais-results>
                <template slot-scope="{ result }">
                    <p>
                        <a :href="result.path">
                            <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                        </a>
                    </p>
                </template>
            </ais-results>
        </ais-index>
    </div>
@endsection