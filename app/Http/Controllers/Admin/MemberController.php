<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->toString();

        $members = User::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                                      ->orWhere('whatsapp', 'like', "%{$search}%")
                                      ->orWhere('email', 'like', "%{$search}%"))
            ->latest()
            ->paginate(25);

        return view('admin.members.index', compact('members', 'search'));
    }

    public function toggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        return redirect()
            ->route('admin.members.index')
            ->with('toast', ['type' => 'success', 'message' => 'Status member diupdate.']);
    }
}
