@extends('layouts.app')

@section('title', 'FocusType - Master English')

@section('content')
  {{-- 全体のフォントをPublic Sans風に、背景を元の淡い色に設定 --}}
  <div class="bg-[#fff8f6] text-[#261813] min-h-screen font-sans">

    <header class="relative overflow-hidden pt-16 pb-24 lg:pt-32 lg:pb-40">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

          <div class="flex flex-col gap-8 max-w-2xl">
            <h1 class="text-5xl lg:text-7xl font-black {{-- font-display --}} text-[#261813] leading-[1.1] tracking-tight">
              Master English Through the <span class="text-[#a33900]">Power of Typing</span>
            </h1>

            <p class="text-lg lg:text-xl text-[#5a4138] leading-relaxed">
              Enhance your IELTS performance and professional communication by building linguistic muscle memory.
              Register now to access all our features completely free of charge.
            </p>
          </div>

          <div class="relative">
            <div class="aspect-[4/3] bg-[#fdfcff] rounded-[2rem] overflow-hidden shadow-2xl border border-[#e2bfb3]/30">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAT5MbZEfQFKdWqXhie2-5P18rCRMmGQL0ZK0B4F4F-_LxWL1u4CxmV41zMjix4d7WFJDXxiM7o7KggCeFTVW1MawA9jYh5m5vbeplMEg6ISOALg0n_yL3xM6pMlbvDK6klxKXoneBrsWbCMab27Eq8kk7z1HzmCz1lmOJmxEw69xh_LaxQUTvQhwDnU5xk-b0kPxzgi_UNrL_zJehHGe13OBfOVq3kCd9usAd3jKKo0hEK3AjlpaPXHaXVOGdnJ9bPtk0WjiyBlto"
                alt="workspace image"
                class="w-full h-full object-cover" />
            </div>
            <div class="absolute inset-0 bg-gradient-to-tr from-[#a33900]/10 to-transparent rounded-[2rem] pointer-events-none"></div>
          </div>

        </div>
      </div>
    </header>

    <section class="py-24 bg-[#fff1ec] border-t border-b border-[#e2bfb3]/20">
      <div class="max-w-7xl mx-auto px-6">

        <div class="mb-16 max-w-2xl">
          <h2 class="text-[#a33900] font-bold uppercase tracking-widest text-sm mb-4">
              Core System for Language Mastery
          </h2>

          <h3 class="text-4xl lg:text-5xl font-black tracking-tight text-[#261813]">
              Designed to improve accuracy, speed, and long-term retention
          </h3>

          <p class="text-[#5a4138] text-lg mt-6 leading-relaxed">
              Our platform transforms typing practice into a structured learning system,
              helping you develop real-world English proficiency through repetition and focus.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          </div>

      </div>
    </section>

    <section class="py-24">
      <div class="max-w-5xl mx-auto px-6">
        <div class="bg-[#3d2d27] rounded-[3rem] p-12 text-center text-white relative overflow-hidden shadow-xl">

          <h2 class="text-4xl lg:text-6xl font-black tracking-tight">
              Ready to transform your English?
          </h2>

          @guest
            {{-- 【ログインしていない時】 --}}
            <p class="text-[#f6ded3] mt-6 text-lg">
                Register to start learning, or log in to continue your progress.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                <a href="{{ route('register') }}"
                   class="bg-[#a33900] text-white px-8 py-4 rounded-xl font-bold hover:opacity-90 transition shadow-md">
                    Create Account
                </a>

                <a href="{{ route('login') }}"
                   class="border border-[#e2bfb3]/40 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/10 transition">
                    Log In & Continue Learning
                </a>
            </div>
          @endguest

          @auth
            {{-- 【ログインしている時】 --}}
            <p class="text-[#f6ded3] mt-6 text-lg">
                Welcome back, <span class="text-[#ffb599] font-bold">{{ Auth::user()->name }}</span>! Ready to practice?
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                {{-- 修正：ログイン後のダッシュボードへ飛ぶように設定 --}}
                <a href="{{ route('dashboard') }}" class="bg-[#a33900] text-white px-8 py-4 rounded-xl font-bold hover:opacity-90 transition shadow-md">
                    Start Typing Game
                </a>

                {{-- 修正：共通レイアウトのログアウトフォームを動かすボタンに設定 --}}
                <button type="button" 
                        class="border border-[#e2bfb3]/40 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/10 transition w-full sm:w-auto"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Log Out
                </button>
            </div>
          @endauth

        </div>
      </div>
    </section>

  </div>
@endsection