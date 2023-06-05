{{--<form action="{{ route('users.index') }}" method="GET">--}}
{{--    <div class="form-group d-flex align-items-center">--}}
{{--        <label for="search" class="mr-2">検索:</label>--}}
{{--        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control">--}}
{{--        <button type="submit" class="btn btn-primary ml-2">検索</button>--}}
{{--    </div>--}}
{{--</form>--}}

<form action="{{ route('users.index') }}" method="GET">
    <div class="form-group d-flex align-items-center">
        <label for="search" class="mr-2">検索:</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control">

        <div class="form-check form-check-inline ml-2">
            <input class="form-check-input" type="checkbox" name="search_user" id="search_user"
                   value="1" {{ request('search_user') ? 'checked' : '' }}>
            <label class="form-check-label" for="search_user">ユーザー</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="search_company" id="search_company"
                   value="1" {{ request('search_company') ? 'checked' : '' }}>
            <label class="form-check-label" for="search_company">会社</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="search_section" id="search_section"
                   value="1" {{ request('search_section') ? 'checked' : '' }}>
            <label class="form-check-label" for="search_section">部署</label>
        </div>

        <button type="submit" class="btn btn-primary ml-2">検索</button>
    </div>
</form>

<script>
    // 一つのチェックボックスしか選択できないようにする
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', (event) => {
            checkboxes.forEach((cb) => {
                if (cb !== event.target) {
                    cb.checked = false;
                }
            });
        });
    });
</script>

<form action="{{ route('users.CSV',  ['search' => request('search'), 'search_company' => request('search_company'), 'search_user' => request('search_user'), 'search_section' => request('search_section'), 'page'=>request('page')]) }}" method="post">
    @csrf
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
    <button type="submit">CSV出力</button>
</form>

{{ $users->links() }}



