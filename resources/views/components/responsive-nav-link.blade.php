@props(['active'])

@php
$classes = ($active ?? false)
? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#F53003] text-start text-base font-semibold text-[#F53003] bg-[#FDF2F2] focus:outline-none focus:text-[#D12A00] focus:bg-[#FCE7E5] focus:border-[#D12A00] transition duration-150 ease-in-out'
: 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:text-slate-900 focus:bg-slate-50 focus:border-slate-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>