@extends('errors.layout')
@section('title', __('Terjadi Kesalahan'))
@section('code', $exception->getStatusCode() ?? 'Error')
@section('message', $exception->getMessage() ?: __('Maaf, terjadi kesalahan yang tidak terduga pada sistem kami.'))
@section('icon')
<div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-slate-800/50 flex items-center justify-center text-slate-500 dark:text-slate-400 mb-2 mx-auto">
    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
</div>
@endsection
