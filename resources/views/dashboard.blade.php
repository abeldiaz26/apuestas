<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-6">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <h1 class="text-2xl font-semibold">Panel de control</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">Administra torneos, equipos, partidos y apuestas desde aquí.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('torneos.index') }}" class="block rounded-3xl border border-neutral-200 bg-white p-6 transition hover:border-indigo-500 hover:bg-indigo-50 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-indigo-400">
                <p class="text-sm uppercase tracking-[0.2em] text-neutral-500 dark:text-neutral-400">Torneos</p>
                <p class="mt-4 text-4xl font-semibold">{{ $torneosCount }}</p>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">Ver y crear torneos</p>
            </a>
            <a href="{{ route('torneos.mine') }}" class="block rounded-3xl border border-neutral-200 bg-white p-6 transition hover:border-emerald-500 hover:bg-emerald-50 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-emerald-400">
                <p class="text-sm uppercase tracking-[0.2em] text-neutral-500 dark:text-neutral-400">Mis Torneos</p>
                <p class="mt-4 text-4xl font-semibold">Mis torneos</p>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">Ver torneos en los que estás habilitado</p>
            </a>
            <a href="{{ route('equipos.index') }}" class="block rounded-3xl border border-neutral-200 bg-white p-6 transition hover:border-indigo-500 hover:bg-indigo-50 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-indigo-400">
                <p class="text-sm uppercase tracking-[0.2em] text-neutral-500 dark:text-neutral-400">Equipos</p>
                <p class="mt-4 text-4xl font-semibold">{{ $equiposCount }}</p>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">Administrar equipos</p>
            </a>
            <a href="{{ route('usuarios.index') }}" class="block rounded-3xl border border-neutral-200 bg-white p-6 transition hover:border-indigo-500 hover:bg-indigo-50 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-indigo-400">
                <p class="text-sm uppercase tracking-[0.2em] text-neutral-500 dark:text-neutral-400">Usuarios</p>
                <p class="mt-4 text-4xl font-semibold">Usuarios</p>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">Crear y administrar usuarios</p>
            </a>
            <a href="{{ route('partidos.index') }}" class="block rounded-3xl border border-neutral-200 bg-white p-6 transition hover:border-indigo-500 hover:bg-indigo-50 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-indigo-400">
                <p class="text-sm uppercase tracking-[0.2em] text-neutral-500 dark:text-neutral-400">Partidos</p>
                <p class="mt-4 text-4xl font-semibold">{{ $partidosCount }}</p>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">Gestión de partidos</p>
            </a>
            <a href="{{ route('apuestas.index') }}" class="block rounded-3xl border border-neutral-200 bg-white p-6 transition hover:border-indigo-500 hover:bg-indigo-50 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-indigo-400">
                <p class="text-sm uppercase tracking-[0.2em] text-neutral-500 dark:text-neutral-400">Apuestas</p>
                <p class="mt-4 text-4xl font-semibold">{{ $apuestasCount }}</p>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">Ver las apuestas</p>
            </a>
        </div>
    </div>
</x-layouts::app>
