<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>EDESSI · Gestión de servicio técnico</title>
<script>try{if(localStorage.getItem('modoOscuro')==='true')document.documentElement.classList.add('dark')}catch(e){}</script>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body x-data="{ menu:false }" @keydown.escape.window="menu=false">
<a class="sr-only focus:not-sr-only" href="#contenido">Ir al contenido</a>
<div class="mobile-shade" x-show="menu" x-cloak @click="menu=false">
</div>
<aside class="sidebar" :class="{open:menu}">
<a class="brand" href="{{ route('dashboard') }}">
<span class="brand-mark">
<img src="{{ asset('images/logo-edessi.jpg') }}" alt="">
</span>
<span>EDESSI<small>SERVICIO TÉCNICO</small>
</span>
</a>
<p class="nav-caption">ESPACIO DE TRABAJO</p>
<nav aria-label="Navegación principal">
@php($links=[['dashboard','grid','Resumen'],['reparaciones.index','tool',auth()->user()->esCliente()?'Mis reparaciones':'Reparaciones'],['equipos.index','monitor',auth()->user()->esCliente()?'Mis equipos':'Equipos']])
@if(!auth()->user()->esCliente()) @php($links=array_merge($links,[['clientes.index','users','Clientes'],['reportes.index','file','Reportes']])) @endif
@if(auth()->user()->esAdmin()) @php($links[]=['usuarios.index','shield','Usuarios']) @endif
@foreach($links as [$route,$icon,$label])<a class="side-link {{ request()->routeIs(explode('.',$route)[0].'*')?'active':'' }}" href="{{ route($route) }}" @if(request()->routeIs(explode('.',$route)[0].'*')) aria-current="page" @endif>
<x-icon :name="$icon"/>{{ $label }}</a>@endforeach
</nav>
<p class="nav-caption">MI CUENTA</p>
<a class="side-link {{ request()->routeIs('profile.*')?'active':'' }}" href="{{ route('profile.edit') }}">
<x-icon name="users"/>Mi perfil</a>
<div class="side-footer">
<strong>Todo en un mismo lugar.</strong>Registro, reparación y seguimiento.<p style="margin-top:18px">EDESSI · Cochabamba, Bolivia</p>
</div>
</aside>
<div class="app-main">
<header class="topbar">
<button class="icon-button mobile-menu" @click="menu=!menu" :aria-expanded="menu" aria-label="Abrir menú">
<x-icon name="menu"/>
</button>
<div class="topbar-title">Sistema de seguimiento <span style="margin:0 10px;color:#ccd4df">/</span> <strong>{{ ['admin'=>'Administración','tecnico'=>'Área técnica','cliente'=>'Área de clientes'][auth()->user()->rol] }}</strong>
</div>
<div class="top-actions">@if(config('app.demo'))<span class="badge badge-diagnostico" title="Datos ficticios de demostración">Demo</span>@endif<span class="muted hidden lg:block" style="font-size:11px">{{ now()->translatedFormat('d \d\e F, Y') }}</span>
<button class="icon-button" aria-label="Cambiar tema" @click="document.documentElement.classList.toggle('dark'); localStorage.setItem('modoOscuro',document.documentElement.classList.contains('dark'))">
<x-icon name="moon"/>
</button>
<a href="{{ route('reparaciones.index',['estado'=>'listo']) }}" class="icon-button" aria-label="Equipos listos">
<x-icon name="bell"/>
</a>
<a class="user-chip" href="{{ route('profile.edit') }}">
<span class="avatar">{{ mb_substr(auth()->user()->nombre,0,1) }}</span>
<span class="user-text">{{ auth()->user()->nombre }}<small>{{ ['admin'=>'Administrador','tecnico'=>'Técnico','cliente'=>'Cliente'][auth()->user()->rol] }}</small>
</span>
</a>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="icon-button" aria-label="Cerrar sesión" title="Cerrar sesión">
<x-icon name="logout"/>
</button>
</form>
</div>
</header>
<main class="page" id="contenido">@isset($header)<div class="page-heading">{{ $header }}</div>@endisset
@if(session('success'))<div class="notice" role="status">{{ session('success') }}</div>@endif
@if(session('status')==='password-updated')<div class="notice" role="status">Contraseña actualizada correctamente.</div>@endif
@if(session('error'))<div class="notice notice-error" role="alert">{{ session('error') }}</div>@endif
@php($validationErrors=collect($errors->getBags())->flatMap(fn($bag)=>$bag->all()))
@if($validationErrors->isNotEmpty())<div class="notice notice-error" role="alert">
<strong>Revisa los datos del formulario</strong>
<ul>@foreach($validationErrors as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>@endif
{{ $slot }}<footer class="footer-note">EDESSI · Sistema de seguimiento y reparación de equipos</footer>
</main>
</div>
</body>
</html>
