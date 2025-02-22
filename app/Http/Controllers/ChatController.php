<?php

namespace App\Http\Controllers;
use App\Models\Message;
use App\Models\User;
use App\Events\ChatMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
{
    // Validasi input
    $request->validate([
        'message' => 'string',
        'attachment' => 'nullable|mimes:jpg,jpeg,png,gif,mp4,webm,ogg|max:10240', // Maksimal 10MB
    ]);

    // Data pesan yang akan disimpan
    $data = [
        'user_id' => Auth::id(),
        'receiver_id' => $request->receiver_id,
        'message' => $request->message,
    ];

    // Simpan file jika ada attachment
    if ($request->hasFile('attachment')) {
        // Simpan file ke dalam folder storage/attachments
        $path = $request->file('attachment')->store('attachments', 'public');
        $data['attachment'] = $path;
    }

    // Simpan data ke dalam tabel pesan
    $message = Message::create($data);
    event(new ChatMessageSent($message));
    // broadcast(new ChatMessageSent($message))->toOthers();

    // Redirect kembali ke halaman chat
    return redirect()->route('chat.show', ['receiver_id' => $request->receiver_id]);
}
public function sendMessageadmin(Request $request)
{
    // Validasi input
    $request->validate([
        'message' => 'required_without:attachment',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm,ogg|max:2048',
        'receiver_id' => 'required|exists:users,id'
    ]);

    // Data pesan yang akan disimpan
    $data = [
        'user_id' => Auth::id(),
        'receiver_id' => $request->receiver_id,
        'message' => $request->message,
    ];

    // Simpan file jika ada attachment
    if ($request->hasFile('attachment')) {
        $path = $request->file('attachment')->store('attachments', 'public');
        $data['attachment'] = $path;
    }

    // Simpan data ke dalam tabel pesan
    $message = Message::create($data);
    event(new ChatMessageSent($message));
    // broadcast(new ChatMessageSent($message))->toOthers();
    // event(new ChatMessageSent($message));
    // Broadcast event dengan data yang benar


    return redirect()->route('chat.showadmin', ['receiverId' => $request->receiver_id]);
}
    public function endChat()
    {
        // Ambil semua chat dari user yang sedang login
        $chats = Message::where('user_id', auth()->id())->get();
        
        if ($chats->count() > 0) {
            // Hapus semua chat dari database
            foreach ($chats as $chat) {
                $chat->delete();
            }
            
            return redirect('/')->with('success', 'Semua chat telah diakhiri');
        }
    
        return redirect('/')->with('error', 'Tidak ada chat aktif yang ditemukan');
    }
    // public function getMessages($receiverId)
    // {
    //     $messages = Message::where(function ($query) use ($receiverId) {
    //         $query->where('user_id', Auth::id())
    //               ->where('receiver_id', $receiverId);
    //     })->orWhere(function ($query) use ($receiverId) {
    //         $query->where('user_id', $receiverId)
    //               ->where('receiver_id', Auth::id());
    //     })->orderBy('created_at', 'asc')->get();

    //     return response()->json($messages);
    // }
    public function showChatAdmin($receiverId)
    {  
        
        // Mengambil daftar user yang pernah chat dengan admin
        $userChats = User::select('users.*')
            ->join('messages', function($join) {
                $join->on('users.id', '=', 'messages.user_id')
                     ->orOn('users.id', '=', 'messages.receiver_id');
            })
            ->where('users.id', '!=', Auth::id())
            ->groupBy('users.id')
            ->get();

        // // Perbaikan: gunakan user pertama jika receiverId null
        $selectedUser = $receiverId ? User::find($receiverId) : $userChats->first();
        $userId = $selectedUser ? $selectedUser->id : null;
        
        $messages = [];
        if ($userId) {
            $messages = Message::where(function($query) use ($userId) {
                $query->where('user_id', Auth::id())
                      ->where('receiver_id', $userId);
            })->orWhere(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
        
        }

        return view('chatadmin', compact('messages', 'userChats', 'selectedUser', 'receiverId'));
    }
    public function showChat(Request $request)
    {
        $receiverId = 1; // Ambil receiver_id dari query string
        $userId = Auth::id();

        // Ambil pesan antara pengguna yang sedang login dan penerima
        $messages = Message::where(function ($query) use ($userId, $receiverId) {
            $query->where('user_id', $userId)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($userId, $receiverId) {
            $query->where('user_id', $receiverId)
                  ->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        return view('chat', compact('messages', 'receiverId'));
    }
}
