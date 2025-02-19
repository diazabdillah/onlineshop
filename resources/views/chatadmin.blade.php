<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with User {{ $receiverId }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
            height: 500px;
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
            width: 25%;
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
    .profile-pic {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
        margin-bottom: 5px;
    }
    
    /* Mengatur container untuk foto profil dan pesan */
    .message-container {
        display: flex;
        align-items: flex-start;
        margin-bottom: 10px;
    }
    
    /* Menyesuaikan margin untuk pesan */
    .message {
        margin-bottom: 0;
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
<div style="display: flex; height: calc(100vh - 20px);">
        <!-- Sidebar untuk daftar user -->
        <div style="width: 300px; background-color: #f1f1f1; padding: 15px; border-right: 1px solid #ddd;">
            <h2>Daftar User Chat</h2>
            <div style="overflow-y: auto; height: calc(100% - 50px);">
                @foreach($userChats as $userChat)
                    <div class="user-chat-item" style="
                        padding: 10px;
                        margin-bottom: 10px;
                        background-color: {{ $receiverId == $userChat->id ? '#e0f7fa' : 'white' }};
                        border-radius: 5px;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
                        onclick="window.location.href='{{ route('chat.showadmin', ['receiverId' => $userChat->id]) }}'">
                        <img src="{{ asset('storage/profile/user-avatar.png') }}" 
                             alt="User Avatar" 
                             style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                        <div>
                            <div style="font-weight: bold;">{{ $userChat->name }}</div>
                            <small style="color: #666;">{{ $userChat->latest_message ? \Illuminate\Support\Str::limit($userChat->latest_message, 30) : 'Belum ada pesan' }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div style="flex: 1; display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
             
<a href="/" class="back-button">
            <button style="padding: 8px 15px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </a>
        <h1 style="margin: 0;">Chat Customer Service</h1>
        <form action="{{ route('chat.end') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" 
                {{ count($messages) == 0 ? 'disabled' : '' }}
                style="padding: 8px 15px; background-color: {{ count($messages) == 0 ? '#dc3545 ' : '#dc3545' }}; color: white; border: none; border-radius: 5px; cursor: {{ count($messages) == 0 ? 'not-allowed' : 'pointer' }};">
                <i class="fas fa-times-circle"></i> Akhiri Chat
            </button>
        </form>
    </div>
    <div id="chat">
      
    @foreach ($messages as $message)
    <div class="message-container">
        
        @if($message->user_id != auth()->id())
            <img src="{{ asset('storage/profile/admin-avatar.png') }}" alt="Admin" class="profile-pic">
        @endif
        
        <div class="message {{ $message->user_id == auth()->id() ? 'sender' : 'receiver' }}">
            <strong style="float:right;">{{ $message->user_id == auth()->id() ? 'Admin' : 'User' }}</strong>
            
            <br>
            {{ $message->message }}

            @if ($message->attachment && Str::endsWith($message->attachment, ['jpg', 'jpeg', 'png', 'gif']))
                <br>
                <img src="{{ asset('storage/' . $message->attachment) }}" alt="Attachment">
            @endif

            @if ($message->attachment && Str::endsWith($message->attachment, ['mp4', 'webm', 'ogg']))
                <br>
                <video controls>
                    <source src="{{ asset('storage/' . $message->attachment) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @endif
            <br>
            <small style="float:left;">{{ $message->created_at->format('H:i:s') }}</small>
        </div>
        
        @if($message->user_id == auth()->id())
            <img src="{{ asset('storage/profile/profile.png') }}" alt="You" class="profile-pic">
        @endif
    </div>
@endforeach
    </div>
    
    <form action="{{ route('chat.sendadmin') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="receiver_id" value="{{ $userChat->id }}">
    <input type="text" name="message" id="messageInput" placeholder="Type your message...">
    <input type="file" name="attachment" id="attachmentInput" accept="image/*,video/*">
    <button type="submit" id="sendButton" disabled style="opacity: 0.6; cursor: not-allowed;">Send</button>
</form>

    </div>
    </div>
    <script>
        const messageInput = document.getElementById('messageInput');
        const attachmentInput = document.getElementById('attachmentInput');
        const sendButton = document.getElementById('sendButton');

        function checkInputs() {
            if (messageInput.value.trim() !== '' || attachmentInput.files.length > 0) {
                sendButton.disabled = false;
                sendButton.style.opacity = '1';
                sendButton.style.cursor = 'pointer';
            } else {
                sendButton.disabled = true;
                sendButton.style.opacity = '0.6';
                sendButton.style.cursor = 'not-allowed';
            }
        }

        messageInput.addEventListener('input', checkInputs);
        attachmentInput.addEventListener('change', checkInputs);
    </script>
</body>
</html>