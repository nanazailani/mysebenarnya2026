@extends('layouts.dashboard')

@section('title', 'Inquiry Progress')

@section('content')
<h2 class="section-title">Inquiry Progress for: {{ $inquiry->subject }}</h2>

<table class="table-auto w-full mt-4 text-sm border">
    <thead class="bg-gray-200 text-left">
        <tr>
            <th class="p-3 border">Date</th>
            <th class="p-3 border">Status</th>
            <th class="p-3 border">Remarks</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($progress as $item)
            <tr>
                <td class="p-3 border">{{ $item->created_at->format('d M Y, h:i A') }}</td>
                <td class="p-3 border">{{ $item->status }}</td>
                <td class="p-3 border">{{ $item->remarks ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-3 border text-center">No progress updates yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
