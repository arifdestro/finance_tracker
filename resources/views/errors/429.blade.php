@extends('errors.layout')
@section('title', __('Terlalu Banyak Permintaan'))
@section('code', '429')
@section('message', __('Anda telah mengirimkan terlalu banyak permintaan dalam waktu singkat. Harap tunggu beberapa saat sebelum mencoba lagi.'))
@section('icon')
<div class="w-24 h-24 rounded-full bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center text-orange-500 dark:text-orange-400 mb-2 mx-auto">
    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
</div>
@endsection
