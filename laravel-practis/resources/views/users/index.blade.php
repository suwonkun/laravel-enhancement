<table>
    <thead>
    <tr>
        <th>会社</th>
        <th>部署</th>
        <th>名前</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->company->name }}</td>
            <td>
                @foreach($user->sections as $section)
                    {{ $section->name }}<br>
                @endforeach
            </td>
            <td>{{ $user->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

    {{ $users->links() }}



