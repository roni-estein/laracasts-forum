
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="form-group">
            <input class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="panel-body">
        <div class="body ">
            <wysiwyg name="body" v-model="form.body" :value="form.body"></wysiwyg>
        </div>
    </div>
    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-xs btn-primary mr-1" @click="update">Update</button>
            <button class="btn btn-xs" @click="resetForm">Cancel</button>

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

<div class="panel panel-default" v-if="!editing">
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                <img src="{{ $thread->creator->avatar_path }}" width="25" class="mr-1">
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted: <span v-text="title"></span>
            </span>
        </div>

    </div>

    <div class="panel-body">
        <div class="body" v-html="this.body"></div>
    </div>
    <div class="panel-footer" v-if="authorize('owns',thread)">
        <button class="btn btn-xs" @click="editing = true">Edit</button>
    </div>
</div>