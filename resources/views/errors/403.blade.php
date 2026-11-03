@extends('errors.layout')
@section('title', __('Akses Ditolak'))
@section('code', '403')
@section('message', __('Maaf, Anda tidak memiliki izin (hak akses) untuk melihat halaman ini.'))
@section('icon')
<div class="w-24 h-24 rounded-full bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-500 dark:text-rose-400 mb-2 mx-auto">
    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
</div>
@endsection
