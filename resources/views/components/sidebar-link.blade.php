@props(['route', 'icon', 'label', 'routeIs' => null])

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
</a>
