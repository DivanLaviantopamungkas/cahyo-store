<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadcastController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->toString();

        $broadcasts = Broadcast::query()
            ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(50);

        return view('admin.broadcasts.index', compact('broadcasts', 'search'));
    }

    public function create()
    {
        return response()->json(['message' => 'Not implemented (view later)']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string'],
            'target' => ['required', 'in:all,active_users,inactive_users,specific'],
            'target_users' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,scheduled,sending,sent,cancelled'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $data['admin_id'] = Auth::guard('admin')->id() ?? $data['admin_id'] ?? 1;

        $broadcast = Broadcast::create($data);

        return response()->json($broadcast, 201);
    }

    public function show(Broadcast $broadcast)
    {
        return response()->json($broadcast);
    }

    public function edit(Broadcast $broadcast)
    {
        return response()->json(['message' => 'Not implemented (view later)', 'data' => $broadcast]);
    }

    public function update(Request $request, Broadcast $broadcast)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'message' => ['sometimes', 'required', 'string'],
            'target' => ['sometimes', 'required', 'in:all,active_users,inactive_users,specific'],
            'target_users' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,scheduled,sending,sent,cancelled'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $broadcast->update($data);

        return response()->json($broadcast);
    }

    public function destroy(Broadcast $broadcast)
    {
        $broadcast->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function send(Broadcast $broadcast)
    {
        // Nanti implement kirim ke WhatsApp / notif in-app via queue.
        $broadcast->update(['status' => 'sending']);

        return response()->json(['message' => 'Queued (implement later)', 'broadcast_id' => $broadcast->id]);
    }
}
