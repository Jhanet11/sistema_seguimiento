<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="referrer" content="no-referrer">
<title>EDESSI · Servicio técnico</title>
<script>try{if(localStorage.getItem('modoOscuro')==='true')document.documentElement.classList.add('dark')}catch(e){}</script>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body>
<div class="guest-shell">
<aside class="guest-story">
<a class="brand" href="{{ route('login') }}">
<span class="brand-mark">
<img src="{{ asset('images/logo-edessi.jpg') }}" alt="">
</span>
<span>EDESSI<small>SERVICIO TÉCNICO</small>
</span>
</a>
<div class="guest-copy">
<span class="eyebrow" style="color:#efba68">CUIDAMOS TU TECNOLOGÍA</span>
<h1>Cada equipo.<br>Cada etapa.<br>Todo bajo control.</h1>
<p>Un espacio para organizar el servicio técnico y acompañar cada reparación, desde la recepción hasta la entrega.</p>
<div class="guest-stage">
<span>Recepción</span>
<x-icon name="arrow"/>
<span>Reparación</span>
<x-icon name="arrow"/>
<span class="ready">Listo</span>
</div>
</div>
<p class="guest-bottom">COCHABAMBA, BOLIVIA · EDESSI</p>
</aside>
<div class="guest-form-wrap">
<div class="guest-top" x-data>
<button class="icon-button" aria-label="Cambiar tema" @click="document.documentElement.classList.toggle('dark');localStorage.setItem('modoOscuro',document.documentElement.classList.contains('dark'))">
<x-icon name="moon"/>
</button>
</div>
<main class="guest-form">@if(session('status'))<div class="notice" role="status">{{ session('status') }}</div>@endif @if($errors->any())<div class="notice notice-error" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif{{ $slot }}</main>
</div>
</div>
</body>
</html>
