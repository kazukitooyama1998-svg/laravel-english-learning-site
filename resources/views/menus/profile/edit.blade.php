@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<main class="py-12">
    <div class="max-w-2xl mx-auto px-6">
        <div class="mb-6">
            <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium">
                <i class="fa-solid fa-arrow-left text-sm"></i>
                <span>Back to Profile</span>
            </a>
        </div>

        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm">
                
                <h2 class="text-2xl font-bold text-on-surface mb-6">Update Profile</h2>
                
                {{-- アバター表示部分を以下のように修正 --}}
                <div class="flex items-center gap-6 mb-6">
                    <div class="col-4">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover">
                            {{-- 削除チェックボックス --}}
                            <div class="mt-2 text-center">
                                <label class="flex items-center justify-center gap-2 text-xs text-error cursor-pointer">
                                    <input type="checkbox" name="remove_avatar" class="rounded border-outline-variant">
                                    Delete photo
                                </label>
                            </div>
                        @else
                            <i class="fa-solid fa-circle-user text-6xl text-secondary"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" name="avatar" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-primary file:text-white hover:file:opacity-90">
                        <div class="text-xs text-on-surface-variant mt-2">Acceptable: jpg, jpeg, png, gif. Max 1048kb.</div>
                        @error('avatar') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-bold text-on-surface mb-1">Name</label>
                        <input type="text" name="name" id="name" class="w-full border-outline-variant rounded-lg" value="{{ old('name', $user->name) }}">
                        @error('name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-on-surface mb-1">E-mail</label>
                        <input type="email" name="email" id="email" class="w-full border-outline-variant rounded-lg" value="{{ old('email', $user->email) }}">
                        @error('email') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="introduction" class="block text-sm font-bold text-on-surface mb-1">Introduction</label>
                        <textarea name="introduction" id="introduction" rows="4" class="w-full border-outline-variant rounded-lg">{{ old('introduction', $user->introduction) }}</textarea>
                        @error('introduction') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="current_password" class="block text-sm font-bold text-on-surface mb-1">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="w-full border-outline-variant rounded-lg">
                        @error('current_password') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="new_password" class="block text-sm font-bold text-on-surface mb-1">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="w-full border-outline-variant rounded-lg">
                        <p class="text-xs text-on-surface-variant">At least 8 characters, letters and numbers.</p>
                        @error('new_password') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-bold text-on-surface mb-1">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full border-outline-variant rounded-lg">
                    </div>

                    <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:opacity-90 transition">
                        Save All Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection