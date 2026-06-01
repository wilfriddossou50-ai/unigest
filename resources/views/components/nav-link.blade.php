@props(['href', 'icon', 'label'])
<a href="{{ $href }}"
    class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->url() == $href ? 'bg-sky-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
    <span class="font-medium">{{ $label }}</span>
</a>