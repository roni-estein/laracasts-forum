<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="form-group">
            <input class="form-control" value="{{ $thread->title }}">
        </div>
    </div>

    <div class="panel-body">
        <div class="body ">
            <textarea rows="10" class="form-control">{{$thread->body}} </textarea>
        </div>
    </div>
    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-xs btn-primary mr-1" @click="editing = false">Update</button>
            <button class="btn btn-xs" @click="editing = false">Cancel</button>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="post" class="ml-auto">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete Thread</button>

                </form>

            @endcan
        </div>

    </div>
</div>

<div class="panel panel-default" v-else="editing">
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                <img src="{{ $thread->creator->avatar_path }}" width="25" class="mr-1">
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                {{$thread->title}}
            </span>
        </div>

    </div>

    <div class="panel-body">
        <div class="body ">{{$thread->body}}</div>
    </div>
    <div class="panel-footer">
        <button class="btn btn-xs" @click="editing = true">Edit</button>
    </div>
</div>