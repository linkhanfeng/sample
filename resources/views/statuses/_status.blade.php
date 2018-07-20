<li class="status-li">
    <a href="{{ route('users.show', $user->id) }}">
        <img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="gravatar"/>
    </a>
    <div class="user text-primary" href="{{ route('users.show', $user->id) }}">{{ $user->name }}</div>
    <div class="timestamp">{{ $status->created_at->diffForHumans() }}</div>
    <p class="content">{{ $status->content }}</p>
    @can('destroy', $status)
      <form action="{{ route('statuses.destroy', $status->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn btn-sm btn-danger status-delete-btn">删除</button>
      </form>
    @endcan
</li>