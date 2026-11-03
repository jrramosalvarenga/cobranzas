@props(['steps', 'id' => 'tour'])

<button
    type="button"
    x-data
    x-on:click="
        if (typeof driver === 'undefined') return;
        const steps = {{ Js::from($steps) }};
        const driverObj = driver.js.driver({
            showProgress: true,
            animate: true,
            nextBtnText: 'Siguiente',
            prevBtnText: 'Anterior',
            doneBtnText: 'Finalizar',
            progressText: 'Paso {{current}} de {{total}}',
            steps: steps.map(s => ({
                element: s.element || undefined,
                popover: {
                    title: s.title,
                    description: s.description,
                    side: s.side || 'bottom',
                    align: s.align || 'start',
                }
            }))
        });
        driverObj.drive();
    "
    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white/80 bg-white/15 rounded-full hover:bg-white/25 hover:text-white transition"
    title="Iniciar tour"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Ayuda
</button>
