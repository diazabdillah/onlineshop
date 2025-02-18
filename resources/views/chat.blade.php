<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with User {{ $receiverId }}</title>
    <style>
        /* Gaya umum */
        #chat {
            width: 100%;
            /* max-width: 100%; */
            margin: 20px auto;
            background-color: #f8f9fa;
            /* border: 1px solid #ddd;
            border-radius: 10px; */
            padding: 15px;
            overflow-y: auto;
            height: 570px;
        }
    
        /* Setiap pesan */
        .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 15px;
            font-size: 14px;
            line-height: 1.5;
            max-width: 75%;
            word-wrap: break-word;
        }
    
        /* Pesan dari pengirim */
        .sender {
            background-color: #e0f7fa;
            color: #004d40;
            align-self: flex-end;
            text-align: right;
            margin-left: auto;
        }
    
        /* Pesan dari penerima */
        .receiver {
            background-color: #e1bee7;
            color: #4a148c;
            text-align: left;
        }
    
        /* Tampilkan waktu pesan lebih kecil */
        .message small {
            font-size: 12px;
            color: #666;
        }
    
        /* Formulir input */
        form {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
    
        input[name="message"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            margin-right: 10px;
        }
    
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }
        .message img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin-top: 5px;
    }
    input[name="attachment"] {
        margin: 5px 10px 5px 0;
    }
    /* Menampilkan video dalam pesan */
    .message video {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin-top: 5px;
    }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Chat with Admin</h1>
    <div id="chat">
        @foreach ($messages as $message)
        <div class="message {{ $message->user_id == auth()->id() ? 'sender' : 'receiver' }}">
            <strong>{{ $message->user_id == auth()->id() ? 'You' : 'User ' . $message->user_id }}:</strong> <br>
            {{ $message->message }}

            <!-- Tampilkan gambar jika ada attachment gambar -->
            @if ($message->attachment && Str::endsWith($message->attachment, ['jpg', 'jpeg', 'png', 'gif']))
                <br>
                <img src="{{ asset('storage/' . $message->attachment) }}" alt="Attachment">
            @endif

            <!-- Tampilkan video jika ada attachment video -->
            @if ($message->attachment && Str::endsWith($message->attachment, ['mp4', 'webm', 'ogg']))
                <br>
                <video controls>
                    <source src="{{ asset('storage/' . $message->attachment) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @endif

            <br>
            <small>{{ $message->created_at->format('H:i:s') }}</small>
        </div>
    @endforeach
    </div>
    
    <form action="{{ route('chat.send') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="receiver_id" value="1">
        <input type="text" name="message" placeholder="Type your message...">
        <input type="file" name="attachment" accept="image/*,video/*">
        <button type="submit">Send</button>
    </form>
    
</body>
</html>