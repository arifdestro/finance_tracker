@props(['route', 'icon', 'label', 'routeIs' => null, 'badge' => null])

@php
    $isActive = request()->routeIs($routeIs ?? $route);
@endphp

<a href="{{ route($route) }}" wire:navigate
   class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
   {{ $isActive
      ? 'active bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
      : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5'
   }}">
    <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-indigo-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
    <span>{{ $label }}</span>
    @if($badge)
        <span class="ml-auto text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md bg-gradient-to-r from-indigo-500 to-fuchsia-500 text-white shadow-sm shadow-indigo-500/20 leading-none">{{ $badge }}</span>
    @endif
</a>
