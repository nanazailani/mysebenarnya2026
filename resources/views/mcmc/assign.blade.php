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
                <option
                    value="{{ $inquiry->id }}"
                    data-full-name="{{ $inquiry->full_name }}"
                    data-subject="{{ $inquiry->subject }}"
                    data-message="{{ $inquiry->message }}"
                    data-source-type="{{ $inquiry->source_type }}"
                    data-source-url="{{ $inquiry->source_url }}"
                    data-status="{{ $inquiry->status }}"
                >
                    #{{ $inquiry->id }} - {{ Str::limit($inquiry->subject, 50) }}
                </option>
            @endforeach
        </select>
    </div>

    <div id="inquiry-details" class="hidden border rounded bg-gray-50 p-4 space-y-3">
        <h3 class="font-semibold text-gray-800">Inquiry Details</h3>

        <div>
            <p class="text-sm font-medium text-gray-600">Full Name</p>
            <p id="detail-full-name" class="text-gray-900"></p>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-600">Subject</p>
            <p id="detail-subject" class="text-gray-900"></p>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-600">Message</p>
            <p id="detail-message" class="text-gray-900 whitespace-pre-line"></p>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-600">Source Type</p>
            <p id="detail-source-type" class="text-gray-900"></p>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-600">Source URL</p>
            <p id="detail-source-url" class="text-gray-900 break-words"></p>
        </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inquirySelect = document.getElementById('inquiry_id');
        const detailsPanel = document.getElementById('inquiry-details');
        const detailFields = {
            fullName: document.getElementById('detail-full-name'),
            subject: document.getElementById('detail-subject'),
            message: document.getElementById('detail-message'),
            sourceType: document.getElementById('detail-source-type'),
            sourceUrl: document.getElementById('detail-source-url'),
        };

        function clearInquiryDetails() {
            Object.values(detailFields).forEach(function (field) {
                field.textContent = '';
            });
            detailsPanel.classList.add('hidden');
        }

        inquirySelect.addEventListener('change', function () {
            const selectedOption = inquirySelect.options[inquirySelect.selectedIndex];

            if (!inquirySelect.value) {
                clearInquiryDetails();
                return;
            }

            detailFields.fullName.textContent = selectedOption.dataset.fullName || '-';
            detailFields.subject.textContent = selectedOption.dataset.subject || '-';
            detailFields.message.textContent = selectedOption.dataset.message || '-';
            detailFields.sourceType.textContent = selectedOption.dataset.sourceType || '-';
            detailFields.sourceUrl.textContent = selectedOption.dataset.sourceUrl || '-';

            detailsPanel.classList.remove('hidden');
        });
    });
</script>
@endsection
