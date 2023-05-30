<form action="{{ route('users.index') }}" method="GET">
    <div class="form-group d-flex align-items-center">
        <label for="search" class="mr-2">検索:</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control">
        <button type="submit" class="btn btn-primary ml-2">検索</button>
    </div>
</form>


<table>
    <thead>
    <tr>
        <th>名前</th>
        <th>会社</th>
        <th>部署</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->company->name }}</td>
            <td>
                @foreach($user->sections as $section)
                    {{ $section->name }}<br>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

    {{ $users->links() }}



