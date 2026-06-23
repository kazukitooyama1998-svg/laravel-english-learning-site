@extends('layouts.app')

@section('content')
<main class="flex-1 flex justify-center px-4 py-6">
  <div class="w-full max-w-2xl flex flex-col h-[80vh]">

    {{-- チャットヘッダー --}}
    <div class="mb-4 flex items-center justify-between border-b border-outline-variant pb-4">
      <h1 class="text-title-lg font-bold text-on-surface">Chat with {{ $chatPartner->name }}</h1>
      <a href="{{ route('partners.index') }}" class="text-sm text-primary font-semibold">Back to Connections</a>
    </div>

    {{-- 💡 ここで相互フォローか最終確認（View側の表示ガード） --}}
    @if(Auth::user()->isMutualFollow($chatPartner->id))
        {{-- メッセージ表示エリア --}}
        <div class="flex-1 overflow-y-auto bg-surface-container-low rounded-xl p-4 space-y-4 mb-4 shadow-inner">
          @forelse($messages as $msg)
            <div class="flex {{ $msg->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
              <div class="max-w-[75%] px-4 py-2 rounded-2xl text-body-md shadow-sm {{ $msg->sender_id === Auth::id() ? 'bg-primary text-on-primary rounded-tr-none' : 'bg-surface-container-high text-on-surface rounded-tl-none' }}">
                {{ $msg->body }}
                <p class="text-[10px] opacity-70 mt-1 {{ $msg->sender_id === Auth::id() ? 'text-right' : 'text-left' }}">
                  {{ $msg->created_at->format('H:i') }}
                </p>
              </div>
            </div>
          @empty
            <p class="text-center text-on-surface-variant text-sm mt-10">Start your conversation!</p>
          @endforelse
        </div>

        {{-- メッセージ送信フォーム --}}
        <form action="{{ route('message.store', $room->id) }}" method="POST" class="flex gap-2">
          @csrf
          <input 
            type="text" 
            name="message" 
            required 
            placeholder="Type a message..." 
            class="flex-1 px-4 py-3 bg-surface-container-lowest border border-outline rounded-full outline-none focus:border-primary"
          >
          <button type="submit" class="px-6 py-3 bg-primary text-on-primary font-bold rounded-full hover:bg-primary/90 transition-colors">
            Send
          </button>
        </form>
    @else
        {{-- 相互フォローが解除された場合の表示 --}}
        <div class="flex-1 flex items-center justify-center text-on-surface-variant">
            <p>You are no longer mutual friends. Chat is unavailable.</p>
        </div>
    @endif

  </div>
</main>
@endsection