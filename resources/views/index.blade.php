@extends('layouts.app')

@section('title', 'Axis English - 英語が、新しいチャンスを開く。')

@section('content')
  {{-- 全体のフォントをPublic Sans風に、背景を元の淡い色に設定 --}}
  <div class="bg-[#fff8f6] text-[#261813] min-h-screen font-sans">

    <header class="relative overflow-hidden pt-16 pb-24 lg:pt-32 lg:pb-40">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

          <div class="flex flex-col gap-8 max-w-2xl">
            <h1 class="text-5xl lg:text-7xl font-black {{-- font-display --}} text-[#261813] leading-[1.1] tracking-tight">
              英語が、<span class="text-[#a33900]">新しいチャンス</span>を開く。
            </h1>

            <p class="text-lg lg:text-xl text-[#5a4138] leading-relaxed">
              English Opens New Opportunities.<br class="hidden lg:block">
              タイピング学習で英語を「体で覚える」。TOEIC・IELTS対策から日常のコミュニケーションまで、
              続けるほど力になる学習体験を今すぐ無料でお試しください。
            </p>
          </div>

          <div class="relative">
            <div class="aspect-[4/3] bg-[#fdfcff] rounded-[2rem] overflow-hidden shadow-2xl border border-[#e2bfb3]/30">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAT5MbZEfQFKdWqXhie2-5P18rCRMmGQL0ZK0B4F4F-_LxWL1u4CxmV41zMjix4d7WFJDXxiM7o7KggCeFTVW1MawA9jYh5m5vbeplMEg6ISOALg0n_yL3xM6pMlbvDK6klxKXoneBrsWbCMab27Eq8kk7z1HzmCz1lmOJmxEw69xh_LaxQUTvQhwDnU5xk-b0kPxzgi_UNrL_zJehHGe13OBfOVq3kCd9usAd3jKKo0hEK3AjlpaPXHaXVOGdnJ9bPtk0WjiyBlto"
                alt="学習イメージ"
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
          <h2 class="text-[#a33900] font-bold uppercase tracking-widest text-lg lg:text-xl mb-4">
              今、英語を学ぶ理由
          </h2>

          <h3 class="text-4xl lg:text-5xl font-black tracking-tight text-[#261813]">
              円安、昇進、進学、<span class="inline-block whitespace-nowrap">外資系就職。</span><br class="hidden lg:block">
              英語が、あなたの可能性を<span class="inline-block whitespace-nowrap">広げる。</span>
          </h3>

          <p class="text-[#5a4138] text-lg mt-6 leading-relaxed">
              急速に進む円安により、海外との経済格差はますます広がっています。
              今や英語力は、社内での昇進・昇給の条件として、また大学院進学や海外進学に欠かせないスキルとして、
              多くの場面で求められるようになりました。さらに、給与水準の高い外資系企業への就職においても、
              実践的な英語力は避けて通れません。英語は、これからの時代を生き抜くための「稼ぐ力」であり、
              あなたのキャリアと人生の選択肢を大きく広げる武器になります。
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
              今すぐ、学習をスタートしよう。
          </h2>

          @guest
            {{-- 【ログインしていない時】 --}}
            <p class="text-[#f6ded3] mt-6 text-lg">
                思い立った今が、始めどきです。無料登録して、たった今から英語学習をスタートしましょう。
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                <a href="{{ route('register') }}"
                   class="bg-[#a33900] text-white px-8 py-4 rounded-xl font-bold hover:opacity-90 transition shadow-md">
                    無料で登録する
                </a>

                <a href="{{ route('login') }}"
                   class="border border-[#e2bfb3]/40 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/10 transition">
                    ログインして続ける
                </a>
            </div>
          @endguest

          @auth
            {{-- 【ログインしている時】 --}}
            <p class="text-[#f6ded3] mt-6 text-lg">
                おかえりなさい、<span class="text-[#ffb599] font-bold">{{ Auth::user()->name }}</span>さん！今日も練習を始めましょう。
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                {{-- 修正：ログイン後のダッシュボードへ飛ぶように設定 --}}
                <a href="{{ route('dashboard') }}" class="bg-[#a33900] text-white px-8 py-4 rounded-xl font-bold hover:opacity-90 transition shadow-md">
                    タイピングゲームを始める
                </a>

                {{-- 修正：共通レイアウトのログアウトフォームを動かすボタンに設定 --}}
                <button type="button"
                        class="border border-[#e2bfb3]/40 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/10 transition w-full sm:w-auto"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    ログアウト
                </button>
            </div>
          @endauth

        </div>
      </div>
    </section>

  </div>
@endsection