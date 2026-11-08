@extends('layouts.app')
@section('title', 'Mengarahkan')
@section('content')
<div class="container py-5 text-center"><div class="empty-state"><i class="bi bi-arrow-repeat fs-1 mb-3"></i><p class="mb-0">Membuka ReShare...</p></div></div>
<script>window.location.replace(@json(auth()->check() ? route('dashboard') : route('login')));</script>
@endsection
