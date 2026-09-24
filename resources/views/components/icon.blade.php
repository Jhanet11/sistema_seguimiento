@props(['name'=>'grid'])
@php($paths = [
'grid'=>'M3 3h7v7H3z M14 3h7v7h-7z M3 14h7v7H3z M14 14h7v7h-7z',
'tool'=>'M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4L15 12l-3-3z',
'monitor'=>'M3 4h18v13H3z M8 21h8 M12 17v4',
'users'=>'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2 M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8 M22 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75',
'file'=>'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M8 13h8 M8 17h5',
'plus'=>'M12 5v14 M5 12h14','arrow'=>'M5 12h14 M13 6l6 6-6 6',
'clock'=>'M12 8v4l3 2 M22 12a10 10 0 1 0-20 0 10 10 0 0 0 20 0',
'check'=>'M20 6L9 17l-5-5','search'=>'M21 21l-6-6 M17 10a7 7 0 1 0-14 0 7 7 0 0 0 14 0',
'logout'=>'M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4 M16 17l5-5-5-5 M21 12H9',
'moon'=>'M21 12.8A9 9 0 0 1 11.2 3 9 9 0 1 0 21 12.8',
'menu'=>'M3 6h18 M3 12h18 M3 18h18','download'=>'M12 3v12 M7 10l5 5 5-5 M5 17v4h14v-4',
'shield'=>'M12 3l9 4v6c0 5-9 9-9 9S3 18 3 13V7z M8 12l3 3 5-6',
'bell'=>'M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9 M10 21h4',
'edit'=>'M12 20H4v-8 M16 3l5 5-10 10-6 1 1-6z'
])
<svg {{ $attributes->class(['icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['grid'] }}"/></svg>
