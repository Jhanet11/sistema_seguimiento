<x-app-layout>
<x-slot name="header">
<div>
<h1>Mi perfil</h1>
<p>Mantén tus datos y tu contraseña actualizados.</p>
</div>
<a class="btn btn-secondary" href="{{ route('dashboard') }}">Volver</a>
</x-slot>
<div class="form-shell stack">
<section class="panel">
<div class="panel-head">
<h2>Información personal</h2>
</div>
<form method="POST" action="{{ route('profile.update') }}" class="panel-body">@csrf @method('PATCH')<div class="form-grid">
<div class="field span-2">
<label for="nombre">Nombre completo *</label>
<input id="nombre" name="nombre" type="text" value="{{ old('nombre', $user->nombre) }}" required >
</div>@if(!$user->esCliente())<div class="field span-2">
<label for="email">Correo electrónico *</label>
<input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required >
</div>@else<p class="muted span-2">Tu usuario de acceso es tu C.I.: <strong>{{ $user->email }}</strong>. El taller administra este dato.</p>@endif</div>
<div class="form-actions">
<button class="btn btn-primary">Guardar cambios</button>
</div>
</form>
</section>
<section class="panel">
<div class="panel-head">
<h2>Cambiar contraseña</h2>
</div>
<form method="POST" action="{{ route('password.update') }}" class="panel-body">@csrf @method('PUT')<div class="form-grid">
<div class="field span-2">
<label for="current_password">Contraseña actual *</label>
<input id="current_password" name="current_password" type="password" value="{{ old('current_password') }}" required autocomplete="current-password">
</div>
<div class="field">
<label for="password">Nueva contraseña *</label>
<input id="password" name="password" type="password" value="{{ old('password') }}" required minlength="8" autocomplete="new-password">
</div>
<div class="field">
<label for="password_confirmation">Confirmar nueva contraseña *</label>
<input id="password_confirmation" name="password_confirmation" type="password" value="{{ old('password_confirmation') }}" required minlength="8" autocomplete="new-password">
</div>
</div>
<div class="form-actions">
<button class="btn btn-primary">Actualizar contraseña</button>
</div>
</form>
</section>
</div>
</x-app-layout>
