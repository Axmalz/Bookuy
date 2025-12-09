<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $tab = $request->query('tab', 'penjual');

        // Query untuk mendapatkan list chat terakhir per user
        $subquery = Message::select(DB::raw('LEAST(sender_id, receiver_id) as user_1, GREATEST(sender_id, receiver_id) as user_2, MAX(id) as last_msg_id'))
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->groupBy(DB::raw('LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id)'));

        $threads = DB::table(DB::raw("({$subquery->toSql()}) as threads"))
            ->mergeBindings($subquery->getQuery())
            ->join('messages', 'threads.last_msg_id', '=', 'messages.id')
            ->orderBy('messages.created_at', 'desc')
            ->get();

        $chats = [];

        foreach ($threads as $thread) {
            $partnerId = ($thread->user_1 == $userId) ? $thread->user_2 : $thread->user_1;
            $partner = User::find($partnerId);

            if (!$partner) continue;

            // Logika Tab Penjual vs Pembeli (DIPERBAIKI)
            // Cek pesan pertama di thread ini untuk menentukan inisiator
            $firstMsg = Message::where(function($q) use ($userId, $partnerId) {
                $q->where('sender_id', $userId)->where('receiver_id', $partnerId);
            })->orWhere(function($q) use ($userId, $partnerId) {
                $q->where('sender_id', $partnerId)->where('receiver_id', $userId);
            })->oldest()->first();

            // Definisi Inisiator: Orang yang mengirim pesan PERTAMA kali.
            $isInitiator = ($firstMsg && $firstMsg->sender_id == $userId);

            // LOGIKA BARU:
            // Jika saya Inisiator (saya chat duluan) -> Saya bertindak sebagai PEMBELI -> Lawan bicara adalah PENJUAL.
            // Jadi chat ini harus masuk ke tab "Penjual" (Daftar Penjual yang saya hubungi).

            // Jika saya BUKAN Inisiator (orang lain chat saya) -> Saya bertindak sebagai PENJUAL -> Lawan bicara adalah PEMBELI.
            // Jadi chat ini harus masuk ke tab "Pembeli" (Daftar Pembeli yang menghubungi saya).

            if ($tab == 'penjual') {
                // Tampilkan orang-orang yang SAYA hubungi (karena mereka penjualnya)
                if (!$isInitiator) continue;
            } elseif ($tab == 'pembeli') {
                // Tampilkan orang-orang yang menghubungi SAYA (karena mereka pembelinya)
                if ($isInitiator) continue;
            }

            $unreadCount = Message::where('sender_id', $partnerId)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();

            $chats[] = (object) [
                'user' => $partner,
                'last_message' => $thread->message ?? 'Sent a photo',
                'time' => \Carbon\Carbon::parse($thread->created_at),
                'unread' => $unreadCount
            ];
        }

        return view('chat.index', compact('chats', 'tab'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $partner = User::findOrFail($id);

        Message::where('sender_id', $id)
            ->where('receiver_id', $user->id)
            ->update(['is_read' => true]);

        $messages = Message::where(function($q) use ($user, $id) {
                $q->where('sender_id', $user->id)->where('receiver_id', $id);
            })
            ->orWhere(function($q) use ($user, $id) {
                $q->where('sender_id', $id)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy(function($msg) {
                return $msg->created_at->format('Y-m-d');
            });

        return view('chat.show', compact('partner', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:3072'
        ]);

        if (!$request->message && !$request->file('image')) return back();

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat-images', 'public');
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'image_path' => $path
        ]);

        return back();
    }
}
