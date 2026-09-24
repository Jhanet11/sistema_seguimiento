<x-app-layout>
<x-slot name="header">
<div>
<h1>{{ $usuario->exists?'Editar usuario':'Crear usuario' }}</h1>
<p>Administra el acceso del personal de EDESSI.</p>
</div>
<a class="btn btn-secondary" href="{{ route('usuarios.index') }}">Volver</a>
</x-slot>
<div class="form-shell">
<section class="panel">
<div class="panel-head">
<h2>Cuenta del personal</h2>
</div>
<form class="panel-body" method="POST" action="{{ $usuario->exists?route('usuarios.update',$usuario):route('usuarios.store') }}">@csrf @if($usuario->exists) @method('PUT') @endif<div class="form-grid">
<div class="field span-2">
<label for="nombre">Nombre completo *</label>
<input id="nombre" name="nombre" type="text" value="{{ old('nombre', $usuario->nombre) }}" required maxlength="255">
</div>
<div class="field">
<label for="email">Correo electrónico *</label>
<input id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}" required maxlength="255">
</div>
<div class="field">
<label for="rol">Rol *</label>
<select name="rol" id="rol" required>
<option value="tecnico" @selected(old('rol',$usuario->rol)==='tecnico')>Técnico</option>
<option value="admin" @selected(old('rol',$usuario->rol)==='admin')>Administrador</option>
</select>
</div>
<div class="field">
<label for="password">Contraseña</label>
<input id="password" name="password" type="password" value="{{ old('password') }}"  minlength="8" autocomplete="new-password">
</div>
<div class="field">
<label for="password_confirmation">Confirmar contraseña</label>
<input id="password_confirmation" name="password_confirmation" type="password" value="{{ old('password_confirmation') }}"  minlength="8" autocomplete="new-password">
</div>
<p class="muted span-2" style="font-size:12px">Usa al menos 8 caracteres. {{ $usuario->exists?'Deja la contraseña vacía para conservar la actual.':'' }}</p>
</div>
<div class="form-actions">
<button class="btn btn-primary">Guardar usuario</button>
<a class="btn btn-secondary" href="{{ route('usuarios.index') }}">Cancelar</a>
</div>
</form>
</section>
</div>
</x-app-layout>
