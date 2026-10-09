@props(['role'])

@php
    $colors = [
        'admin' => 'bg-red-100 text-red-700',
        'editor' => 'bg-yellow-100 text-yellow-800',
        'user' => 'bg-gray-100 text-gray-700',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'px-2 py-0.5 rounded text-xs font-semibold ' . ($colors[$role] ?? $colors['user'])]) }}>
    {{ $role }}
</span>
