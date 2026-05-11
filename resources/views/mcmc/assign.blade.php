@extends('layouts.dashboard')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Assign Inquiry')

@section('content')
<h2 class="text-xl font-bold mb-4">Assign Inquiry to Agency</h2>

@if (session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('assign.inquiry') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label for="inquiry_id" class="block font-medium">Select Inquiry</label>
        <select name="inquiry_id" id="inquiry_id" required class="w-full border p-2 rounded">
            <option value="">-- Choose Inquiry --</option>
            @foreach($inquiries as $inquiry)
                <option value="{{ $inquiry->id }}">
                    #{{ $inquiry->id }} - {{ Str::limit($inquiry->subject, 50) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="agency_id" class="block font-medium">Select Agency</label>
        <select name="agency_id" id="agency_id" required class="w-full border p-2 rounded">
            <option value="">-- Choose Agency --</option>
            @foreach($agencies as $agency)
                <option value="{{ $agency->id }}">
                    {{ $agency->agency_name ?? 'No Agency' }} ({{ $agency->email }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="notes" class="block font-medium">Assignment Notes (Optional)</label>
        <textarea name="notes" id="notes" class="w-full border p-2 rounded" rows="3" placeholder="Enter any notes..."></textarea>
    </div>

    <div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Assign Inquiry</button>
    </div>
</form>
@endsection
