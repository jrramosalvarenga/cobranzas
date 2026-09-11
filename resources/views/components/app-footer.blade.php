@props(['class' => ''])

<footer {{ $attributes->merge(['class' => 'text-center text-xs text-gray-400 dark:text-gray-500 py-4 '.$class]) }}>
    &copy; {{ date('Y') }} Todos los derechos reservados por Kontanos Soft.
</footer>
