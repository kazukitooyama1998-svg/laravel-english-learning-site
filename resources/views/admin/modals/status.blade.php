{{-- x-show で表示/非表示を切り替え --}}
<div x-show="showModal" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50" 
     style="display: none;">
    
    {{-- モーダル本体 --}}
    <div @click.away="showModal = false" class="bg-surface p-6 rounded-2xl shadow-xl max-w-sm w-full border border-outline-variant">
        <h3 class="text-lg font-bold {{ $user->trashed() ? 'text-green-600' : 'text-error' }}">
            <i class="fa-solid {{ $user->trashed() ? 'fa-user-check' : 'fa-user-slash' }}"></i> 
            {{ $user->trashed() ? 'Activate' : 'Deactivate' }} User
        </h3>
        
        <p class="mt-4 text-on-surface-variant">
            Are you sure you want to {{ $user->trashed() ? 'activate' : 'deactivate' }} <span class="font-bold">{{ $user->name }}</span>?
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <button @click="showModal = false" class="px-4 py-2 bg-surface-container-high rounded-xl hover:bg-surface-container-highest transition-colors">
                Cancel
            </button>
            <form action="{{ $user->trashed() ? route('admin.users.activate', $user->id) : route('admin.users.deactivate', $user->id) }}" method="POST">
                @csrf
                @method($user->trashed() ? 'PATCH' : 'DELETE')
                <button type="submit" class="px-4 py-2 {{ $user->trashed() ? 'bg-green-600' : 'bg-error' }} text-white rounded-xl hover:opacity-90 transition-colors">
                    {{ $user->trashed() ? 'Activate' : 'Deactivate' }}
                </button>
            </form>
        </div>
    </div>
</div>