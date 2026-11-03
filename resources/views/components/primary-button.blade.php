<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-animate inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#0d7c5f] to-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-[#0a6b51] hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 active:from-[#085a44] active:to-emerald-800']) }}>
    {{ $slot }}
</button>
