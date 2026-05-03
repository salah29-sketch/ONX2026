<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::with(['client'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(20);
        $messages->appends($request->query());

        return view('admin.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        $message->load(['client']);

        if (!$message->admin_read_at) {
            $message->update([
                'admin_read_at' => now(),
                'status'        => $message->status === 'new' ? 'read' : $message->status,
            ]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'reply' => 'required|string|max:5000',
        ]);

        $message->update([
            'admin_reply'      => $request->reply,
            'admin_replied_at' => now(),
            'status'           => 'replied',
        ]);

        return back()->with('success', 'تم حفظ الرد بنجاح.');
    }

    public function markRead(Message $message)
    {
        $message->update([
            'admin_read_at' => now(),
            'status'        => 'read',
        ]);

        return back()->with('success', 'تم تحديد الرسالة كمقروءة.');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'تم حذف الرسالة.');
    }
}
