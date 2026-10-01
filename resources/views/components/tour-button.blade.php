@props(['steps', 'id' => 'tour'])

<button
    type="button"
    id="tour-btn-{{ $id }}"
    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition"
    title="Iniciar tour"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Ayuda
</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('tour-btn-{{ $id }}');
    if (!btn || typeof driver === 'undefined') return;

    const steps = @json($steps);

    btn.addEventListener('click', function () {
        const driverObj = driver.js.driver({
            showProgress: true,
            animate: true,
            nextBtnText: 'Siguiente',
            prevBtnText: 'Anterior',
            doneBtnText: 'Finalizar',
            progressText: 'Paso @{{current}} de @{{total}}',
            steps: steps.map(function (s) {
                return {
                    element: s.element || undefined,
                    popover: {
                        title: s.title,
                        description: s.description,
                        side: s.side || 'bottom',
                        align: s.align || 'start',
                    }
                };
            })
        });
        driverObj.drive();
    });
});
</script>
