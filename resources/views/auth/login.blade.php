<x-guest-layout>
<span class="eyebrow muted">BIENVENIDO A EDESSI</span>
<h1 style="margin-top:12px">Tu taller, conectado.</h1>
<p class="intro">Inicia sesión para gestionar tus servicios o consultar el estado de tus equipos.</p>
<form method="POST" action="{{ route('login') }}">@csrf<div class="field">
<label for="email">Correo electrónico o C.I.</label>
<input id="email" name="email" value="{{ old('email') }}" placeholder="Ingresa tu correo o cédula" autocomplete="username" required autofocus>
</div>
<div class="field" x-data="{show:false}">
<label for="password">Contraseña</label>
<div class="password-wrap">
<input type="password" :type="show?'text':'password'" id="password" name="password" required autocomplete="current-password" placeholder="Ingresa tu contraseña">
<button type="button" @click="show=!show" :aria-pressed="show" x-text="show?'Ocultar':'Mostrar'">Mostrar</button>
</div>
</div>
<div style="display:flex;justify-content:space-between;gap:12px;font-size:11px;margin:5px 0 25px">
<label>
<input type="checkbox" name="remember"> Recordarme</label>
<a class="link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
</div>
<button class="btn btn-primary">Iniciar sesión <x-icon name="arrow"/>
</button>
</form>
<div class="guest-rule">
</div>
<p class="footnote">¿Dejaste tu equipo en el taller?</p>
<a class="btn btn-secondary" style="margin-top:13px" href="{{ route('seguimiento.index') }}">
<x-icon name="search"/>Consultar una reparación</a>
<p class="footnote" style="font-size:10px">Acceso seguro · Atención cercana · Seguimiento claro</p>
</x-guest-layout>
