@extends('layouts.dashboard')

@section('title', 'User Directory')

@section('content')

    {{-- ===========================
         PAGE HEADING + STATUS
    ============================ --}}
    <h2 class="section-title" style="font-size: 2.25rem; margin-bottom: 24px; font-weight: 700; color: #000;">
        User Directory
    </h2>

    {{-- Show success message after account creation or deletion --}}
    @if(session('status'))
        <div style="background-color: #D1FAE5; color: #065F46; border: 1px solid #10B981; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
    @endif

    {{-- ===========================
         FILTERING & SORTING FORM
    ============================ --}}
    <form method="GET" action="{{ route('mcmc.users') }}"
          style="margin-bottom: 20px; display:flex; gap:20px; flex-wrap: wrap;">

        {{-- Filter by user role --}}
        <div>
            <label for="role_filter" style="font-weight:600; color:#374151;">Filter by Role:</label>
            <select name="role" id="role_filter" style="margin-left: 6px; padding: 8px; border: 1px solid #D1D5DB; border-radius: 6px; font-size: 1rem;">
                <option value="" {{ $filterRole === '' ? 'selected' : '' }}>All</option>
                <option value="public_user" {{ $filterRole === 'public_user' ? 'selected' : '' }}>Public User</option>
                <option value="agency_staff" {{ $filterRole === 'agency_staff' ? 'selected' : '' }}>Agency Staff</option>
                <option value="mcmc_staff" {{ $filterRole === 'mcmc_staff' ? 'selected' : '' }}>MCMC Staff</option>
            </select>
        </div>

        {{-- Sort by created_at date --}}
        <div>
            <label for="sort_order" style="font-weight:600; color:#374151;">Sort by Date:</label>
            <select name="sort" id="sort_order" style="margin-left: 6px; padding: 8px; border: 1px solid #D1D5DB; border-radius: 6px; font-size: 1rem;">
                <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Newest First</option>
                <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Oldest First</option>
            </select>
        </div>

        {{-- Submit filter/sort --}}
        <div style="align-self: flex-end;">
            <button type="submit" style="background-color: #000; color: #fff; padding: 8px 20px; font-size:1rem; font-weight:600; border:none; border-radius:6px; cursor:pointer;">
                Apply
            </button>
        </div>

        {{-- Download user directory PDF --}}
        <div style="align-self: flex-end;">
            <a href="{{ route('mcmc.users.pdf', ['role'=>$filterRole, 'sort'=>$sortOrder]) }}"
               style="background-color: #10B981; color: #fff; padding: 8px 20px; font-size:1rem; font-weight:600; border:none; border-radius:6px; text-decoration:none; display:inline-block;">
                Download PDF
            </a>
        </div>
    </form>

    {{-- ===========================
         USER DIRECTORY TABLE
    ============================ --}}
    <div class="card" style="width:95%; padding:30px; margin-bottom:40px;">
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color:#F3F4F6;">
                    <th style="padding:12px;">#</th>
                    <th style="padding:12px;">Name</th>
                    <th style="padding:12px;">Email</th>
                    <th style="padding:12px;">Role</th>
                    <th style="padding:12px;">Agency</th>
                    <th style="padding:12px;">Joined</th>
                    <th style="padding:12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop through each user --}}
                @forelse($users as $idx => $u)
                    <tr>
                        {{-- Running number --}}
                        <td style="padding:12px;">{{ $users->firstItem() + $idx }}</td>

                        {{-- User name --}}
                        <td style="padding:12px;">{{ $u->name }}</td>

                        {{-- Email --}}
                        <td style="padding:12px;">{{ $u->email }}</td>

                        {{-- Display readable role --}}
                        <td style="padding:12px;">
                            @if($u->role === 'public_user') Public User
                            @elseif($u->role === 'agency_staff') Agency Staff
                            @elseif($u->role === 'mcmc_staff') MCMC Staff
                            @else {{ $u->role }}
                            @endif
                        </td>

                        {{-- Agency name or placeholder --}}
                        <td style="padding:12px;">{{ $u->agency_name ?? '—' }}</td>

                        {{-- Created date --}}
                        <td style="padding:12px;">{{ $u->created_at->format('M d, Y') }}</td>

                        {{-- Delete action (not allowed for self) --}}
                        <td style="padding:12px;">
                            @if(auth()->id() !== $u->id)
                                <button type="button"
                                    onclick="openModal('{{ route('mcmc.users.destroy', $u->id) }}')"
                                    style="background-color: #EF4444; color: #fff; padding: 6px 12px; font-size: 0.9rem; border-radius: 4px; cursor: pointer;">
                                    Delete
                                </button>
                            @else
                                <span style="color:#9CA3AF;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    {{-- No user found --}}
                    <tr>
                        <td colspan="7" style="padding:20px; text-align:center; color:#6B7280;">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Laravel built-in pagination --}}
        <div style="margin-top:20px;">
            {{ $users->links() }}
        </div>
    </div>

    {{-- ===========================
         DELETE CONFIRMATION MODAL
    ============================ --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Confirm Deletion</h3>
            <p class="mb-4">Are you sure you want to delete this user?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===========================
         JAVASCRIPT FOR MODAL LOGIC
    ============================ --}}
    <script>
        // Show delete modal with dynamic form action
        function openModal(actionUrl) {
            const form = document.getElementById('deleteForm');
            form.action = actionUrl;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>

@endsection
