<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.petugas.index', compact('users'));
    }

    /**
     * Show form for creating new staff/petugas.
     */
    public function create()
    {
        return view('admin.petugas.create');
    }

    /**
     * Store new staff/petugas in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'petugas';
        $validated['is_active'] = true;

        $user = User::create($validated);

        // Send welcome email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->queue(new \App\Mail\PetugasAssigned($user));
        } catch (\Exception $e) {
            // ignore mail/queue failures
        }

        return redirect()->route('admin.petugas.index')
                       ->with('success', "Petugas {$user->name} berhasil ditambahkan.");
    }

    /**
     * Show form for editing staff/petugas.
     */
    public function edit(User $user)
    {
        if (!$user->isPetugas()) {
            return redirect()->route('admin.petugas.index')
                           ->with('error', 'User ini bukan petugas.');
        }

        return view('admin.petugas.edit', compact('user'));
    }

    /**
     * Update staff/petugas data.
     */
    public function update(Request $request, User $user)
    {
        if (!$user->isPetugas()) {
            return redirect()->route('admin.petugas.index')
                           ->with('error', 'User ini bukan petugas.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('admin.petugas.index')
                       ->with('success', "Data petugas {$user->name} berhasil diperbarui.");
    }

    /**
     * Delete staff/petugas.
     */
    public function destroy(User $user)
    {
        if (!$user->isPetugas()) {
            return redirect()->route('admin.petugas.index')
                           ->with('error', 'User ini bukan petugas.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.petugas.index')
                       ->with('success', "Petugas {$name} berhasil dihapus.");
    }

    public function promote(User $user)
    {
        $user->role = 'petugas';
        $user->save();

        // Queue notification email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->queue(new \App\Mail\PetugasAssigned($user));
        } catch (\Exception $e) {
            // ignore mail/queue failures
        }

        return redirect()->back()->with('success', "User {$user->name} ditetapkan sebagai Petugas.");
    }

    public function revoke(User $user)
    {
        // prevent revoking the main admin
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Tidak dapat mencabut role admin.');
        }

        $user->role = 'user';
        $user->save();

        return redirect()->back()->with('success', "Role Petugas dicabut untuk {$user->name}.");
    }

    /**
     * Export staff/petugas to CSV.
     */
    public function exportCSV()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        $filename = "daftar-staff-" . now()->format('Y-m-d-His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID', 'Nama', 'Email', 'Role', 'Telepon', 'Alamat', 'Status', 'Terdaftar'
            ]);

            // Data rows
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    $user->phone ?? '-',
                    $user->address ?? '-',
                    $user->is_active ? 'Aktif' : 'Nonaktif',
                    $user->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
