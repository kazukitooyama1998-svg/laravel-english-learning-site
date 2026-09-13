@props([
    'character',           // config/english.php の 'characters.*' 相当の配列（key/body/belly/accent を含む）
    'size' => 'md',        // sm | md | lg | xl
])

@php
    // アイコンの外枠サイズ（px）。Tailwind が読み込まれていないページ
    // （英語の森）でも同じ見た目になるよう、サイズは inline style で指定する。
    $px = match ($size) {
        'sm' => 40,
        'lg' => 96,
        'xl' => 112,
        default => 56,
    };

    $body   = $character['body']   ?? '#a1815f';
    $belly  = $character['belly']  ?? '#fdfbf5';
    $accent = $character['accent'] ?? '#4a3627';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex rounded-full overflow-hidden shrink-0 shadow-sm ring-2 ring-white']) }}
      style="width:{{ $px }}px;height:{{ $px }}px;border-radius:9999px;overflow:hidden;display:inline-flex;flex-shrink:0;"
      title="{{ $character['name'] ?? '' }}">
    <svg viewBox="0 0 64 64" style="width:100%;height:100%;display:block" role="img" aria-label="{{ $character['name'] ?? 'キャラクター' }}">
        <circle cx="32" cy="32" r="32" fill="{{ $body }}"/>

        @switch($character['key'] ?? null)
            {{-- きつね：三角の耳＋長めの鼻づら --}}
            @case('fox')
                <path d="M10 14L24 26 6 28z" fill="{{ $body }}"/>
                <path d="M14 18L23 27 12 27z" fill="{{ $belly }}"/>
                <path d="M54 14L40 26 58 28z" fill="{{ $body }}"/>
                <path d="M50 18L41 27 52 27z" fill="{{ $belly }}"/>
                <ellipse cx="32" cy="38" rx="17" ry="15" fill="{{ $body }}"/>
                <path d="M32 38q13 0 15 12q-15 6-30 0q2-12 15-12z" fill="{{ $belly }}"/>
                <circle cx="24" cy="36" r="2.6" fill="{{ $accent }}"/>
                <circle cx="40" cy="36" r="2.6" fill="{{ $accent }}"/>
                <path d="M29 47q3 3 6 0" stroke="{{ $accent }}" stroke-width="2" fill="none" stroke-linecap="round"/>
                <ellipse cx="32" cy="44" rx="2.4" ry="1.8" fill="{{ $accent }}"/>
                @break

            {{-- ねこ：三角の耳＋ひげ --}}
            @case('cat')
                <path d="M14 12L26 26 10 26z" fill="{{ $body }}"/>
                <path d="M50 12L38 26 54 26z" fill="{{ $body }}"/>
                <path d="M18 16L25 25 16 24z" fill="{{ $accent }}" opacity=".55"/>
                <path d="M46 16L39 25 48 24z" fill="{{ $accent }}" opacity=".55"/>
                <circle cx="32" cy="38" r="18" fill="{{ $body }}"/>
                <path d="M32 40q11 0 13 10q-13 5-26 0q2-10 13-10z" fill="{{ $belly }}"/>
                <circle cx="25" cy="36" r="2.6" fill="#3a332b"/>
                <circle cx="39" cy="36" r="2.6" fill="#3a332b"/>
                <path d="M32 42l-2.6 2h5.2z" fill="{{ $accent }}"/>
                <path d="M10 39h9M10 43h8M45 39h9M46 43h8" stroke="{{ $accent }}" stroke-width="1.4" stroke-linecap="round" opacity=".6"/>
                @break

            {{-- くま：丸い耳＋大きな鼻先 --}}
            @case('bear')
                <circle cx="15" cy="17" r="8" fill="{{ $body }}"/>
                <circle cx="49" cy="17" r="8" fill="{{ $body }}"/>
                <circle cx="15" cy="17" r="4" fill="{{ $accent }}" opacity=".5"/>
                <circle cx="49" cy="17" r="4" fill="{{ $accent }}" opacity=".5"/>
                <circle cx="32" cy="38" r="19" fill="{{ $body }}"/>
                <ellipse cx="32" cy="43" rx="11" ry="9" fill="{{ $belly }}"/>
                <circle cx="25" cy="35" r="2.6" fill="#2c2018"/>
                <circle cx="39" cy="35" r="2.6" fill="#2c2018"/>
                <ellipse cx="32" cy="43" rx="3.2" ry="2.4" fill="{{ $accent }}"/>
                @break

            {{-- うさぎ：長い耳 --}}
            @case('rabbit')
                <ellipse cx="21" cy="10" rx="6" ry="16" fill="{{ $body }}" transform="rotate(-10 21 10)"/>
                <ellipse cx="21" cy="12" rx="3" ry="11" fill="{{ $belly }}" transform="rotate(-10 21 12)"/>
                <ellipse cx="43" cy="10" rx="6" ry="16" fill="{{ $body }}" transform="rotate(10 43 10)"/>
                <ellipse cx="43" cy="12" rx="3" ry="11" fill="{{ $belly }}" transform="rotate(10 43 12)"/>
                <circle cx="32" cy="40" r="17" fill="{{ $body }}"/>
                <ellipse cx="32" cy="45" rx="10" ry="8" fill="{{ $belly }}"/>
                <circle cx="25" cy="38" r="2.4" fill="#3a332b"/>
                <circle cx="39" cy="38" r="2.4" fill="#3a332b"/>
                <circle cx="32" cy="44" r="2" fill="{{ $accent }}"/>
                @break

            {{-- たぬき：マスク模様＋丸い耳 --}}
            @case('raccoon')
                <circle cx="16" cy="18" r="7.5" fill="{{ $body }}"/>
                <circle cx="48" cy="18" r="7.5" fill="{{ $body }}"/>
                <circle cx="16" cy="18" r="4" fill="{{ $accent }}"/>
                <circle cx="48" cy="18" r="4" fill="{{ $accent }}"/>
                <circle cx="32" cy="38" r="19" fill="{{ $body }}"/>
                <path d="M15 33q17-9 34 0q-2 9-17 9t-17-9z" fill="{{ $accent }}" opacity=".85"/>
                <ellipse cx="32" cy="44" rx="10" ry="8" fill="{{ $belly }}"/>
                <circle cx="25" cy="36" r="2.4" fill="#fdfbf5"/>
                <circle cx="39" cy="36" r="2.4" fill="#fdfbf5"/>
                <circle cx="25" cy="36" r="1.2" fill="#2c2018"/>
                <circle cx="39" cy="36" r="1.2" fill="#2c2018"/>
                <ellipse cx="32" cy="43" rx="2.6" ry="2" fill="#2c2018"/>
                @break

            {{-- パンダ：黒丸の耳＋目のまわりのパッチ --}}
            @case('panda')
                <circle cx="16" cy="17" r="8" fill="{{ $accent }}"/>
                <circle cx="48" cy="17" r="8" fill="{{ $accent }}"/>
                <circle cx="32" cy="38" r="19" fill="{{ $body }}"/>
                <ellipse cx="24" cy="36" rx="6" ry="7" fill="{{ $accent }}" transform="rotate(-12 24 36)"/>
                <ellipse cx="40" cy="36" rx="6" ry="7" fill="{{ $accent }}" transform="rotate(12 40 36)"/>
                <circle cx="24" cy="37" r="2.2" fill="#fdfbf5"/>
                <circle cx="40" cy="37" r="2.2" fill="#fdfbf5"/>
                <ellipse cx="32" cy="45" rx="2.6" ry="2" fill="{{ $accent }}"/>
                @break

            {{-- ふくろう：大きな丸い目＋耳羽 --}}
            @case('owl')
                <path d="M17 10L25 24 9 22z" fill="{{ $body }}"/>
                <path d="M47 10L39 24 55 22z" fill="{{ $body }}"/>
                <circle cx="32" cy="38" r="19" fill="{{ $body }}"/>
                <circle cx="23" cy="37" r="8" fill="{{ $belly }}"/>
                <circle cx="41" cy="37" r="8" fill="{{ $belly }}"/>
                <circle cx="23" cy="37" r="3.4" fill="#2c2018"/>
                <circle cx="41" cy="37" r="3.4" fill="#2c2018"/>
                <path d="M29 44l3 4 3-4z" fill="{{ $accent }}"/>
                @break

            {{-- かえる：頭の上に飛び出た目 --}}
            @case('frog')
                <circle cx="20" cy="16" r="8" fill="{{ $body }}"/>
                <circle cx="44" cy="16" r="8" fill="{{ $body }}"/>
                <circle cx="20" cy="16" r="3.4" fill="#2c2018"/>
                <circle cx="44" cy="16" r="3.4" fill="#2c2018"/>
                <ellipse cx="32" cy="40" rx="20" ry="16" fill="{{ $body }}"/>
                <ellipse cx="32" cy="46" rx="12" ry="8" fill="{{ $belly }}"/>
                <path d="M20 44q12 8 24 0" stroke="{{ $accent }}" stroke-width="2.2" fill="none" stroke-linecap="round"/>
                @break

            @default
                <circle cx="32" cy="38" r="18" fill="{{ $belly }}"/>
                <circle cx="32" cy="30" r="9" fill="{{ $belly }}"/>
        @endswitch
    </svg>
</span>
