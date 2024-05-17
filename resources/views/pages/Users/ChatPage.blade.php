@extends('layouts.Root')
@section('content')
<header class="sticky top-0 left-0 flex justify-center w-full bg-white z-10 shadow-md py-8">
    <a href="{{'/'}}" class="absolute top-5 left-5 flex items-center gap-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#F9832A" class="bi bi-arrow-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10.354 1.646a.5.5 0 0 1 0 .708L5.707 7l4.647 4.646a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708 0z" />
        </svg>
        <h1 class="font-bold text-black text-xl mb-1">Chat</h1>
    </a>
</header>
<main class="flex flex-col gap-5 px-5 mt-3">
    <form action="{{ route('room.chat') }}" method="POST">
        @csrf
        <div class="flex flex-col gap-5" id="chat-list">
            {{-- Chat list will be dynamically updated here --}}
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Initialize Firebase
        var firebaseConfig = {
            apiKey: "AIzaSyBJK-ziJOe-oMgSkjI5MJK16OO0LjQDMQQ",
            authDomain: "tester-6b415.firebaseapp.com",
            databaseURL: "https://tester-6b415-default-rtdb.asia-southeast1.firebasedatabase.app",
            projectId: "tester-6b415",
            storageBucket: "tester-6b415.appspot.com",
            messagingSenderId: "829911681243",
            appId: "1:829911681243:web:f6e4657da628304752e4fe",
            measurementId: "G-7PCGVXL2MX"
        };
        firebase.initializeApp(firebaseConfig);

        // Get a reference to the Firebase database
        var database = firebase.database();
        var custId = "{{ $custId }}";

        function handleSubmitButtonClick(courId) {
            $('input[name="courId"]').val(courId);

            $('form').submit();
            chatRef = database.ref('chats/' + custId + '-' + courId + '/courNewMssg');
            chatRef.set(0);
        }

        var countMssg;
        var courId;

        var custIdRef = database.ref('chats');
        custIdRef.on('child_added', function(snapshot) {
            var lastMessageQuery = snapshot.ref.orderByChild('msgs/timestamp').limitToLast(1);
            var id = snapshot.key.split('-')[0];
            if (id == custId) {
                courId = snapshot.key.split('-')[1];
                (function(courId) {
                    database.ref('chats/' + custId + '-' + courId).on('value', function(csnapshot) {
                        countMssg = csnapshot.val().courNewMssg;
                    });
                    database.ref('chats/' + custId + '-' + courId + '/courNewMssg').on('child_changed', function(countsnapshot) {
                        countMssg = countsnapshot.val();
                        if (countMssg != 0) {
                            $('div[data-count-id="' + courId + '"] .count-message').empty();
                            $('div[data-count-id="' + courId + '"] .count-message').text(countMssg);
                            $('div[data-count-id="' + courId + '"]').removeClass('hidden');
                        } else {
                            $('div[data-count-id="' + courId + '"]').addClass('hidden');
                        }
                    });
                    lastMessageQuery.on('child_added', function(lastMessageSnapshot) {
                        console.log(lastMessageSnapshot.val())
                        var mssgData = lastMessageSnapshot.val();
                        var messageData = mssgData.msgs;
                        if (messageData) {
                            console.log(custId, courId)
                            var sender = snapshot.val().cour_name;
                            var message = messageData.msg;
                            var timestamp = messageData.timestamp;
                            var date = new Date(timestamp);
                            var hours = date.getHours().toString().padStart(2, '0');
                            var minutes = date.getMinutes().toString().padStart(2, '0');
                            var formattedTimestamp = hours + ':' + minutes;
                            chatItem = createChatItem(sender, message, formattedTimestamp, courId, countMssg);
                            chatList = $('#chat-list');
                            chatItems = chatList.children('.chat-item');
                            existingChatItem = chatItems.find('.chat-item[data-cour-id="' + courId + '"]');
                            if (existingChatItem.length > 0) {
                                existingChatItem.find('.message').text(message);
                                existingChatItem.find('.timestamp').text(formattedTimestamp);
                                $('.chat-item[data-del-id="' + courId + '"]').remove();
                                $('#chat-list').prepend(chatItem);
                            } else {
                                displayLatestMessage(sender, message, timestamp, courId, countMssg);
                            }
                        }
                    });
                })(courId);
            }
        });

        function displayLatestMessage(sender, message, timestamp, courId, countMssg) {

            var chatItem = createChatItem(sender, message, timestamp, courId, countMssg);

            var chatList = $('#chat-list');
            var chatItems = chatList.children('.chat-item');

            if (chatItems.length > 0) {
                var newestMessageTimestamp = timestamp;
                var inserted = false;
                chatItems.each(function() {
                    var existingTimestamp = $(this).find('.timestamp').val();
                    if (newestMessageTimestamp > existingTimestamp) {
                        $(this).before(chatItem);
                        inserted = true;
                        return false;
                    }
                });
                if (!inserted) {
                    chatList.append(chatItem);
                }
            } else {
                chatList.append(chatItem);
            }

        }

        function createChatItem(sender, message, timestamp, courId, countMssg) {
            var date = new Date(timestamp);
            var hours = date.getHours().toString().padStart(2, '0');
            var minutes = date.getMinutes().toString().padStart(2, '0');
            var formattedTimestamp = hours + ':' + minutes;

            var containerDiv = $('<div>').addClass('chat-item').attr('data-cour-id', courId);
            var chatItemDiv = $('<div>').addClass('flex justify-between items-center gap-3');

            var senderInfoDiv = $('<div>').addClass('flex flex-col gap-1 items-start');
            var senderName = $('<h1>').addClass('font-bold text-black').text(sender);
            var messageText = $('<p>').addClass('text-gray-400 message').text(message);
            senderInfoDiv.append(senderName, messageText);

            var timestampDiv = $('<div>').addClass('flex flex-col items-end gap-1 right-info');
            var timestampText = $('<p>').addClass('text-gray-400 timestamp').text(formattedTimestamp).attr('value', timestamp);
            if (countMssg != 0) {
                var unreadCountDiv = $('<div>').addClass('w-5 h-5 rounded-full bg-orange-500 flex items-center justify-center').attr('data-count-id', courId);
                var unreadCount = $('<a>').addClass('text-white text-xs font-bold count-message').text(countMssg);
                unreadCountDiv.append(unreadCount);
                timestampDiv.append(timestampText, unreadCountDiv);
            } else {
                timestampDiv.append(timestampText);
            }

            chatItemDiv.append(senderInfoDiv, timestampDiv);

            var submitButton = $('<button>')
                .attr('type', 'submit').attr('data-del-id', courId)
                .addClass('w-full chat-item')
                .click(function() {
                    handleSubmitButtonClick(courId);
                });

            var hiddenInput = $('<input>').addClass('hidden').attr('type', 'hidden').attr('name', 'courId');

            containerDiv.append(chatItemDiv, hiddenInput);

            submitButton.append(containerDiv);

            return submitButton;
        }
    </script>
</main>
@endsection