<?php

namespace App\Http\Controllers;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validasi input
        $request->validate([
            'message' => 'string',
            'receiver_id' => 'required|exists:users,id',
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
        Message::create($data);
    
        // Redirect kembali ke halaman chat
        return redirect()->route('chat.show', ['receiver_id' => $request->receiver_id]);
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
