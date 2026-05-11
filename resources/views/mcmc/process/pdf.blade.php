<!DOCTYPE html>
<html>
<head>
    <title>Agency Performance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Agency Performance Report</h2>

    <table>
        <thead>
            <tr>
                <th>Agency</th>
                <th>Total Assigned</th>
                <th>Resolved</th>
                <th>Pending</th>
                <th>Avg Resolution Time (Days)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $agency => $data)
                <tr>
                    <td>{{ $agency }}</td>
                    <td>{{ $data['assigned'] }}</td>
                    <td>{{ $data['resolved'] }}</td>
                    <td>{{ $data['pending'] }}</td>
                    <td>{{ round($data['avg_days'], 1) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
