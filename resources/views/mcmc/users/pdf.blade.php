{{-- resources/views/mcmc/users/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Directory Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            font-size: 24px;
        }
        .small { font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <h1>User Directory Report</h1>
    <p class="small">
        Filter: 
        @if($filterRole === '') 
            All Roles 
        @else 
            {{ ucfirst(str_replace('_',' ',$filterRole)) }} 
        @endif
        &nbsp;|&nbsp;
        Sort: {{ $sortOrder === 'asc' ? 'Oldest First' : 'Newest First' }}
        <br>
        Generated: {{ \Carbon\Carbon::now()->format('M d, Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:25%;">Name</th>
                <th style="width:30%;">Email</th>
                <th style="width:15%;">Role</th>
                <th style="width:15%;">Agency</th>
                <th style="width:10%;">Joined</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $idx => $u)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @if($u->role === 'public') 
                            Public 
                        @elseif($u->role === 'agency_staff') 
                            Agency Staff 
                        @elseif($u->role === 'mcmc_staff') 
                            MCMC Staff 
                        @else 
                            {{ $u->role }}
                        @endif
                    </td>
                    <td>{{ $u->agency_name ?? '–' }}</td>
                    <td>{{ $u->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
