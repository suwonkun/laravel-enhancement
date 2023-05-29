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



