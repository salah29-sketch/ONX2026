<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client\ClientMessage;
use Illuminate\Http\Request;

class ClientMessagesController extends Controller
{
    public function index()
    {
        $messages = ClientMessage::with('client')->latest()->paginate(20);
        return view('admin.client-messages.index', compact('messages'));
    }

    public function show(ClientMessage $message)
    {
        $message->load('client');
        if (!$message->admin_read_at) {
            $message->update(['admin_read_at' => now()]);
        }
        return view('admin.client-messages.show', compact('message'));
    }

    public function reply(Request $request, ClientMessage $message)
    {
        $request->validate([
            'reply' => 'required|string|max:5000',
        ]);
        $message->update([
            'admin_reply'      => $request->reply,
            'admin_replied_at' => now(),
        ]);
        return back()->with('message', 'تم حفظ الرد بنجاح.');
    }

    public function markRead(ClientMessage $message)
    {
        $message->update(['admin_read_at' => now()]);
        return back()->with('message', 'تم تحديد الرسالة كمقروءة.');
    }
}
