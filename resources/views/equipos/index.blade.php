<x-layouts::app :title="__('Equipos')">
    <div class="space-y-6">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <h1 class="text-xl font-semibold">Equipos</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">Agrega equipos y asigna el torneo correspondiente.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <h2 class="text-lg font-semibold">Nuevo equipo</h2>
                <form action="{{ route('equipos.store') }}" method="post" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Bandera / emoji</label>
                        <input type="text" name="flag" value="{{ old('flag') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" placeholder="Ej. 🇦🇷" />
                        @error('flag')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Torneo</label>
                        <select name="tournament_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                            <option value="">Seleccione un torneo</option>
                            @foreach($torneos as $torneo)
                                <option value="{{ $torneo->id }}">{{ $torneo->name }}</option>
                            @endforeach
                        </select>
                        @error('tournament_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Guardar</button>
                </form>
            </section>

            <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <h2 class="text-lg font-semibold">Lista de equipos</h2>
                <div class="mt-4 space-y-3">
                    @forelse($equipos as $equipo)
                        <div class="rounded-2xl border border-neutral-200 p-4 dark:border-neutral-800">
                            <form action="{{ route('equipos.update', $equipo) }}" method="post" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Nombre</label>
                                    <input type="text" name="name" value="{{ $equipo->name }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Bandera</label>
                                    <input type="text" name="flag" value="{{ $equipo->flag }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Torneo</label>
                                    <select name="tournament_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                                        @foreach($torneos as $torneo)
                                            <option value="{{ $torneo->id }}" @selected($torneo->id === $equipo->tournament_id)>{{ $torneo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Actualizar</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">No hay equipos registrados.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-layouts::app>
