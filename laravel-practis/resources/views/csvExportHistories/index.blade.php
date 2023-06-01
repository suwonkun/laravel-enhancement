<div class="container">
    <h1>csvダウンロード履歴</h1>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>ファイル名</th>
            <th>作成日</th>
            <th>更新日</th>
        </tr>
        </thead>
        <tbody>
        @foreach($histories as $history)
            <tr>
                <td>{{ $history->id }}</td>
                <td>{{ $history->file_name }}</td>
                <td>{{ $history->created_at }}</td>
                <td>{{ $history->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
