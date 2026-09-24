<x-app-layout>
<x-slot name="header">
<div>
<h1>{{ $cliente->exists?'Editar cliente':'Registrar cliente' }}</h1>
<p>Datos de contacto y acceso al seguimiento.</p>
</div>
<a class="btn btn-secondary" href="{{ route('clientes.index') }}">Volver</a>
</x-slot>
<div class="form-shell">
<section class="panel">
<div class="panel-head">
<h2>Información del cliente</h2>
</div>
<form class="panel-body" method="POST" action="{{ $cliente->exists?route('clientes.update',$cliente):route('clientes.store') }}">@csrf @if($cliente->exists) @method('PATCH') @endif<div class="form-grid">
<div class="field span-2">
<label for="nombre">Nombre completo *</label>
<input id="nombre" name="nombre" type="text" value="{{ old('nombre', $cliente->nombre) }}" required maxlength="255" autocomplete="name">
</div>
<div class="field">
<label for="ci">Cédula de identidad *</label>
<input id="ci" name="ci" type="text" value="{{ old('ci', $cliente->ci) }}" required maxlength="30">
</div>
<div class="field">
<label for="telefono">Teléfono</label>
<input id="telefono" name="telefono" type="tel" value="{{ old('telefono', $cliente->telefono) }}"  maxlength="30">
</div>
<div class="field">
<label for="correo_notificacion">Correo para notificaciones</label>
<input id="correo_notificacion" name="correo_notificacion" type="email" value="{{ old('correo_notificacion', $cliente->correo_notificacion) }}"  maxlength="255">
</div>
<div class="field">
<label for="direccion">Dirección</label>
<input id="direccion" name="direccion" type="text" value="{{ old('direccion', $cliente->direccion) }}"  maxlength="255">
</div>@if(auth()->user()->esAdmin())<div class="span-2" x-data="{access:{{ old('crear_acceso',$cliente->usuario_id?1:0)?'true':'false' }}}">
<label style="display:flex;align-items:center;gap:10px;font-weight:600">
<input type="checkbox" name="crear_acceso" value="1" x-model="access">{{ $cliente->usuario_id?'Actualizar contraseña de acceso (opcional)':'Crear cuenta para el cliente' }}</label>
<p class="muted" style="font-size:11px;margin:8px 0 18px">El cliente ingresará con su C.I. y la contraseña que definas. El correo de contacto también puede usarse si identifica una sola cuenta.</p>
<div class="form-grid" x-show="access">
<div class="field">
<label for="password">Contraseña</label>
<input id="password" name="password" type="password" value="{{ old('password') }}"  minlength="8" autocomplete="new-password">
</div>
<div class="field">
<label for="password_confirmation">Confirmar contraseña</label>
<input id="password_confirmation" name="password_confirmation" type="password" value="{{ old('password_confirmation') }}"  minlength="8" autocomplete="new-password">
</div>
</div>
@if($cliente->usuario_id)<div class="field" style="margin-top:20px">
<label for="acceso_activo">Estado de la cuenta</label>
<select name="acceso_activo" id="acceso_activo">
<option value="1" @selected(old('acceso_activo',$cliente->usuario->activo)==1)>Activo</option>
<option value="0" @selected(old('acceso_activo',$cliente->usuario->activo)==0)>Inactivo</option>
</select>
</div>@endif
</div>@else<p class="muted span-2" style="font-size:12px">El administrador puede habilitar una cuenta de acceso. El cliente también puede consultar con su comprobante.</p>@endif</div>
<div class="form-actions">
<button class="btn btn-primary">Guardar cliente</button>
<a class="btn btn-secondary" href="{{ route('clientes.index') }}">Cancelar</a>
</div>
</form>
</section>
</div>
</x-app-layout>
