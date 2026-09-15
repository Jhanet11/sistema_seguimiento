@props(['texto' => 'Guardar', 'textoEnviando' => 'Guardando...'])

<button type="submit"
        :disabled="enviando"
        {{ $attributes->merge(['class' => 'px-4 py-2 bg-edessi-600 text-white rounded-lg
                       transition-all duration-150 hover:bg-edessi-700 dark:hover:bg-edessi-500
                       hover:shadow-md active:scale-95
                       disabled:opacity-70 disabled:cursor-not-allowed
                       flex items-center justify-center gap-2']) }}>
    <svg x-show="enviando" x-cloak class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg>
    <span x-text="enviando ? '{{ $textoEnviando }}' : '{{ $texto }}'"></span>
</button>