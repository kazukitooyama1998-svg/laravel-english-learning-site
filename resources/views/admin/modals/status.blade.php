{{-- x-show で表示/非表示を切り替え --}}
<div x-show="showModal" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm h-screen w-screen" 
     style="display: none; position: fixed; top: 0; left: 0;">
    
    {{-- モーダル本体 --}}
    <div @click.away="showModal = false" class="bg-surface p-6 rounded-3xl shadow-2xl max-w-sm w-full border border-outline-variant text-center my-auto mx-auto">
        
        {{-- タイトル --}}
        <h3 class="text-xl font-bold {{ $user->trashed() ? 'text-primary' : 'text-error' }} flex items-center justify-center">
            <i class="fa-solid {{ $user->trashed() ? 'fa-user-check' : 'fa-user-slash' }} mr-2"></i> 
            {{ $user->trashed() ? 'Activate' : 'Deactivate' }} User
        </h3>
        
        {{-- テキスト --}}
        <p class="mt-4 text-on-surface-variant text-body-md">
            Are you sure you want to {{ $user->trashed() ? 'activate' : 'deactivate' }} <span class="font-semibold text-on-surface">{{ $user->name }}</span>?
        </p>

        {{-- ボタンエリア --}}
        <div class="mt-8 flex justify-center gap-3">
            <button @click="showModal = false" class="px-5 py-2.5 bg-surface-container-high rounded-xl hover:bg-surface-container-highest font-bold transition-colors">
                Cancel
            </button>
            
            <form action="{{ $user->trashed() ? route('admin.users.activate', $user->id) : route('admin.users.deactivate', $user->id) }}" method="POST">
                @csrf
                @method($user->trashed() ? 'PATCH' : 'DELETE')
                
                <button type="submit" class="px-5 py-2.5 {{ $user->trashed() ? 'bg-primary text-on-primary' : 'bg-error text-white' }} rounded-xl font-bold hover:opacity-90 transition-colors shadow-sm">
                    {{ $user->trashed() ? 'Activate' : 'Deactivate' }}
                </button>
            </form>
        </div>
    </div>
</div>