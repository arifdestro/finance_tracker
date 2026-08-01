@extends('errors.layout')
@section('title', __('Kesalahan Server'))
@section('code', '500')
@section('message', __('Mohon maaf, sistem kami sedang mengalami gangguan internal. Kami sedang berusaha memperbaikinya. Silakan coba beberapa saat lagi.'))
@section('icon')
<div class="w-24 h-24 rounded-full bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-500 dark:text-rose-400 mb-2 mx-auto">
    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
</div>
@endsection
