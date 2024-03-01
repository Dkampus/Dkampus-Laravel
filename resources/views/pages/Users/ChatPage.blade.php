@extends('layouts.Root')
@section('content')
    <?php
        // temporary data chats
    $chats = [
        [
            'sender' => 'Driver y',
            'message' => 'Halo, ada yang bisa kami bantu?',
            'time' => '12:00'
        ],
        [
            'sender' => 'Driver x',
            'message' => 'Halo, saya ingin memesan makanan',
            'time' => '12:00'
        ],
        [
            'sender' => 'Admin z',
            'message' => 'Halo, apa ada keluhan ?',
            'time' => '01:00'
        ]
    ];
    ?>
    <header class="sticky top-0 left-0 flex justify-center w-full bg-white z-10 shadow-md py-8">
        <a href="{{ url()->previous() }}" class="absolute top-5 left-5 flex items-center gap-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#F9832A" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10.354 1.646a.5.5 0 0 1 0 .708L5.707 7l4.647 4.646a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708 0z"/>
            </svg>
            <h1 class="font-bold text-black text-2xl">Chat</h1>
        </a>
    </header>
    <main class="flex flex-col gap-5 px-5 mt-5">
        <div class="flex flex-col gap-5">
            {{--fetch data chat from database chat ?--}}
            @foreach($chats as $chat)
                <div class="flex justify-between items-center gap-3">
                    <div class="flex flex-row gap-3">
                        <img src="{{ asset('images/ProfilePicture.png') }}" alt="" class="w-16 h-16 rounded-full">
                        <div class="flex flex-col gap-1">
                            <h1 class="font-bold text-black">{{ $chat['sender'] }}</h1>
                            <p class="text-gray-400">{{ $chat['message'] }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <p class="text-gray-400">{{ $chat['time'] }}</p>
                        <div class="w-5 h-5 bg-[#F9832A] rounded-full"><a class="">1</a></div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection
