@extends('layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Submit an Inquiry</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('inquiry.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block font-medium">Full Name</label>
            <input name="full_name" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Phone Number</label>
            <input name="phone_number" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Email Account</label>
            <input name="email" type="email" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Subject</label>
            <input name="subject" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Additional Info / Message</label>
            <textarea name="message" rows="5" class="w-full border rounded p-2" required></textarea>
        </div>



        <div class="mb-4">
            <label class="block font-medium">Sources</label>
            <select name="source_type" class="w-full border rounded p-2" required>
                <option value="">-- Choose Sources --</option>
                <option value="Facebook">Facebook</option>
                <option value="WhatsApp">WhatsApp</option>
                <option value="Twitter">Twitter</option>
                <option value="Others">Others</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">URL</label>
            <input name="source_url" type="url" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Attachment (PDF/Image)</label>
            <input name="attachment" type="file" class="w-full">
        </div>

        <div class="flex justify-between items-center mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Submit
            </button>

        <a href="{{ route('dashboard.public') }}" class="text-blue-600 hover:underline ml-auto">
        Cancel & Go Back →
        </a>
        </div>


    </form>
</div>
@endsection
