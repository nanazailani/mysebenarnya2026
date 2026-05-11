<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inquiry Assignment Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Inquiry Assignment Report</h2>
    <table>
        <thead>
            <tr>
                <th>Agency</th>
                <th>Month</th>
                <th>Year</th>
                <th>Total Inquiries</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $row)
                <tr>
                    <td>{{ $row->agency_name }}</td>
                    <td>{{ date('F', mktime(0, 0, 0, $row->month, 1)) }}</td>
                    <td>{{ $row->year }}</td>
                    <td>{{ $row->total_inquiries }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
