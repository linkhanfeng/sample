<form action="{{ route('statuses.store') }}" method="POST" class="status_create_form">
    @include('shared._errors')
    {{ csrf_field() }}
    <textarea class="form-control" placeholder="新鲜事" name="content" rows="3">{{ old('content') }}</textarea>
    <button type="submit" class="btn btn-primary pull-right submitbtn">发布</button>
</form>