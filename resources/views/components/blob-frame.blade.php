@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'relative overflow-hidden bg-rose-50/60 flex items-center justify-center ' . $class]) }}>
    {{ $slot }}
</div>