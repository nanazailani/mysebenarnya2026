<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users for MCMC (with optional filtering & sorting).
     */
    public function index(Request $request)
    {
        // Initialize a new query for the User model
        $query = User::query();

        // ──────────────────────────────────────────────
        // 1. FILTER: by user role (public, agency, mcmc)
        // ──────────────────────────────────────────────
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // ──────────────────────────────────────────────
        // 2. SORT: based on created_at (asc / desc)
        // ──────────────────────────────────────────────
        if ($request->input('sort') === 'asc') {
            $query->orderBy('created_at', 'asc');
        } elseif ($request->input('sort') === 'desc') {
            $query->orderBy('created_at', 'desc');
        } else {
            // Default: show latest users first
            $query->orderBy('created_at', 'desc');
        }

        // ──────────────────────────────────────────────
        // 3. Paginate the result (10 per page)
        // ──────────────────────────────────────────────
        $users = $query->paginate(10)->withQueryString();

        // ──────────────────────────────────────────────
        // 4. Return to user directory view with data
        // ──────────────────────────────────────────────
        return view('mcmc.users.index', [
            'users'      => $users,
            'filterRole' => $request->input('role', ''),
            'sortOrder'  => $request->input('sort', ''),
        ]);
    }

    /**
     * Generate and return a PDF of the current user directory.
     */
    public function downloadPdf(Request $request)
    {
        // Reuse same logic as index() to get filtered/sorted users
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->input('sort') === 'asc') {
            $query->orderBy('created_at', 'asc');
        } elseif ($request->input('sort') === 'desc') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $allUsers = $query->get();

        // Render the user list using Blade template and generate PDF
        $pdf = PDF::loadView('mcmc.users.pdf', [
            'users'      => $allUsers,
            'filterRole' => $request->input('role', ''),
            'sortOrder'  => $request->input('sort', ''),
        ])->setPaper('a4', 'landscape');

        // Return PDF as downloadable file
        return $pdf->download('user-directory.pdf');
    }

    /**
     * Show the “Create Agency Staff” form to MCMC staff.
     */
    public function showCreateForm()
    {
        // Display create-account Blade page
        return view('mcmc.users.create-account');
    }

    /**
     * Store a newly created agency_staff user.
     */
    public function storeNewAgency(Request $request)
    {
        // ──────────────────────────────────────────────
        // 1. Validate input fields
        // ──────────────────────────────────────────────
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'agency_name' => 'required|string|max:255',
        ]);

        // ──────────────────────────────────────────────
        // 2. Create the new agency user with default values
        // ──────────────────────────────────────────────
        User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'role'           => 'agency_staff',
            'agency_name'    => $data['agency_name'],
            'is_first_login' => 1, // Enforce first-time password setup
        ]);

        // ──────────────────────────────────────────────
        // 3. Redirect back with success message
        // ──────────────────────────────────────────────
        return redirect()
            ->route('mcmc.create-account')
            ->with('status', 'New agency staff account created successfully.');
    }

    /**
     * Delete a user account (only by MCMC Staff).
     */
    public function destroy($id)
    {
        // ──────────────────────────────────────────────
        // 1. Find user or throw 404
        // ──────────────────────────────────────────────
        $user = User::findOrFail($id);

        // ──────────────────────────────────────────────
        // 2. Prevent self-deletion by current MCMC staff
        // ──────────────────────────────────────────────
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // ──────────────────────────────────────────────
        // 3. Delete user and redirect with status
        // ──────────────────────────────────────────────
        $user->delete();

        return redirect()->route('mcmc.users')->with('status', 'User account deleted successfully.');
    }
}
