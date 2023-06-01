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
        @foreach($csvExportHistories as $csvExportHistory)
            <tr>
                <td>{{ $csvExportHistory->id }}</td>
                <td>
                <td>{{ Html::linkRoute('csv-export-history.download', $csvExportHistory->file_name, $csvExportHistory) }}</td>
                </td>
                <td>{{ $csvExportHistory->created_at }}</td>
                <td>{{ $csvExportHistory->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
