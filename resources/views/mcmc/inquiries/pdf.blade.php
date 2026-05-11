<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inquiry Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #444;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-pending {
            color: #d97706; /* orange */
        }
        .status-approved {
            color: #059669; /* green */
        }
        .status-rejected {
            color: #dc2626; /* red */
        }
    </style>
</head>
<body>
    <h2>Inquiry Submission Report</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Subject</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inquiries as $index => $inquiry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $inquiry->subject }}</td>
                    <td>{{ $inquiry->full_name }}</td>
                    <td>{{ $inquiry->email }}</td>
                    <td class="status-{{ strtolower($inquiry->status) }}">{{ ucfirst($inquiry->status) }}</td>
                    <td>{{ \Carbon\Carbon::parse($inquiry->created_at)->format('d M Y, h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
