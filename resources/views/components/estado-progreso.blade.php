@php($keys=array_keys(\App\Models\Reparacion::ESTADOS))
<div class="progress-track" aria-label="Progreso de la reparación">
@foreach(\App\Models\Reparacion::ESTADOS as $key=>$label)
<div class="progress-step {{ $loop->index<=array_search($estado,$keys)?'done':'' }} {{ $key===$estado?'current':'' }}" @if($key===$estado) aria-current="step" @endif><span class="step-dot">{{ $loop->index<array_search($estado,$keys)?'✓':$loop->iteration }}</span>{{ $label }}</div>
@endforeach</div>
