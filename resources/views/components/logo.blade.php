@props(['size' => 'md', 'showText' => true])

@php
$sizes = [
    'sm' => ['icon' => 'w-8 h-8', 'text' => 'text-lg'],
    'md' => ['icon' => 'w-12 h-12', 'text' => 'text-xl'],
    'lg' => ['icon' => 'w-16 h-16', 'text' => 'text-2xl'],
];
$iconSize = $sizes[$size]['icon'];
$textSize = $sizes[$size]['text'];
@endphp

<div class="flex items-center gap-3">
    <div class="{{ $iconSize }} relative">
        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="100" height="100" rx="20" fill="#2E7D32" />
            <path d="M50 25C35 25 30 30 30 40C30 45 32 48 35 50C32 52 30 55 30 60C30 70 35 75 50 75C65 75 70 70 70 60C70 55 68 52 65 50C68 48 70 45 70 40C70 30 65 25 50 25Z" fill="white" opacity="0.3" />
            <path d="M35 42C35 38 37 35 42 35H58C63 35 65 38 65 42V43C65 47 63 50 58 50H42C37 50 35 47 35 43V42Z" fill="#A5D6A7" />
            <path d="M38 55C38 53 39 52 41 52H59C61 52 62 53 62 55V58C62 62 60 65 55 65H45C40 65 38 62 38 58V55Z" fill="#C8E6C9" />
            <circle cx="45" cy="42" r="2" fill="#2E7D32" />
            <circle cx="55" cy="42" r="2" fill="#2E7D32" />
            <path d="M44 58C44 58 46 60 50 60C54 60 56 58 56 58" stroke="#2E7D32" stroke-width="2" stroke-linecap="round" />
            <path d="M35 28C35 28 40 22 50 22C60 22 65 28 65 28" stroke="#A5D6A7" stroke-width="3" stroke-linecap="round" />
            <ellipse cx="42" cy="27" rx="3" ry="4" fill="#81C784" />
            <ellipse cx="50" cy="25" rx="3" ry="4" fill="#66BB6A" />
            <ellipse cx="58" cy="27" rx="3" ry="4" fill="#81C784" />
        </svg>
    </div>
    @if($showText)
    <div>
        <h1 class="{{ $textSize }} leading-none mb-0">
            <span class="text-[#2E7D32]">Doo</span>
            <span class="text-gray-900">nates</span>
        </h1>
        <p class="text-xs text-gray-500">Share. Reduce. Impact.</p>
    </div>
    @endif
</div>
