@props([
'role',
'content',
])

@php
$isUser = $role === 'user';
@endphp

<div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }}">
    <div @class([ 'max-w-[85%] rounded-2xl px-4 py-3 text-sm leading-6 shadow-sm' , 'bg-sky-500 text-white'=> $isUser,
        'border border-slate-800 bg-slate-900 text-slate-200' => ! $isUser,
        ])
        >
        <div class="mb-1 text-[11px] font-semibold uppercase tracking-wider opacity-60">
            {{ $isUser ? 'Tú' : 'ShopMate' }}
        </div>

        <div>
            {!! nl2br(e($content)) !!}
        </div>
    </div>
</div>