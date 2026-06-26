@extends('layouts.app')

@section('title', 'Admin: Users')

@section('content')
<main class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-12">
    
    <div class="flex justify-end mb-6">
        <form action="{{ route('admin.search') }}" method="GET" class="flex items-center gap-2 w-full md:w-[350px]">
            {{-- 検索入力 --}}
            <input type="search" name="keyword" 
                value="{{ request('keyword') }}" 
                class="w-full px-4 py-2 border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent" 
                placeholder="Search users...">
            
            {{-- リセットボタン（検索ワードがある時だけ表示） --}}
            @if(request()->filled('keyword'))
                <a href="{{ route('admin.users') }}" 
                class="px-4 py-2 bg-surface-container-high text-on-surface-variant rounded-xl hover:bg-surface-container-highest transition-colors">
                Clear
                </a>
            @else
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-xl hover:opacity-90 transition-colors">
                    Search
                </button>
            @endif
        </form>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-variant text-on-surface-variant uppercase text-caption font-bold">
                <tr>
                    <th class="p-4"></th>
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Created At</th>
                    <th class="p-4">Status</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @foreach ($all_users as $user)
                    {{-- 💡 x-data="{ showModal: false }" をここに追加 --}}
                    <tr class="hover:bg-surface-container-low transition-colors" x-data="{ showModal: false }">
                        <td class="p-4">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="size-10 rounded-full object-cover">
                            @else
                                <div class="size-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                                    <i class="fa-solid fa-circle-user text-xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="p-4 font-semibold text-on-surface">
                            <a href="{{ route('profile.show', $user->id) }}" class="hover:text-primary no-underline">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td class="p-4 text-on-surface-variant">{{ $user->email }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="p-4">
                            @if ($user->trashed())
                                <span class="flex items-center gap-2 text-secondary text-sm">
                                    <i class="fa-solid fa-circle text-[8px]"></i> Inactive
                                </span>
                            @else
                                <span class="flex items-center gap-2 text-green-600 text-sm">
                                    <i class="fa-solid fa-circle text-[8px]"></i> Active
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            @if (Auth::user()->id !== $user->id)
                                {{-- 💡 ボタンクリックで showModal を true に変更 --}}
                                <button type="button" @click="showModal = true" class="text-on-surface-variant hover:text-primary">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                
                                {{-- 💡 ここでモーダルを読み込み --}}
                                @include('admin.modals.status')
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex justify-center">
        {{ $all_users->links() }}
    </div>
</main>
@endsection