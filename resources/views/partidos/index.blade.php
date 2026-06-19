<x-layouts::app :title="__('Partidos')">
    <div class="space-y-6">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <h1 class="text-xl font-semibold">Partidos</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">Registra partidos con fecha, puntajes y estado.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <h2 class="text-lg font-semibold">Nuevo partido</h2>
                <form action="{{ route('partidos.store') }}" method="post" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Equipo 1</label>
                        <select name="equipo1_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                            <option value="">Seleccione equipo 1</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->id }}">{{ $equipo->flag }} {{ $equipo->name }}</option>
                            @endforeach
                        </select>
                        @error('equipo1_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Equipo 2</label>
                        <select name="equipo2_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                            <option value="">Seleccione equipo 2</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->id }}">{{ $equipo->flag }} {{ $equipo->name }}</option>
                            @endforeach
                        </select>
                        @error('equipo2_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Torneo</label>
                        <select name="torneo_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                            <option value="">Seleccione un torneo</option>
                            @foreach($torneos as $torneo)
                                <option value="{{ $torneo->id }}">{{ $torneo->name }}</option>
                            @endforeach
                        </select>
                        @error('torneo_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Fecha y hora</label>
                        <input type="datetime-local" name="fecha_hora" value="{{ old('fecha_hora') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                        @error('fecha_hora')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Puntaje equipo 1</label>
                        <input type="number" name="equipo1_puntaje" min="0" value="{{ old('equipo1_puntaje') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" />
                        @error('equipo1_puntaje')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Puntaje equipo 2</label>
                        <input type="number" name="equipo2_puntaje" min="0" value="{{ old('equipo2_puntaje') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" />
                        @error('equipo2_puntaje')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Estado</label>
                        <input type="text" name="estado" value="{{ old('estado', 'pendiente') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                        @error('estado')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Guardar</button>
                </form>
            </section>

            <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <h2 class="text-lg font-semibold">Lista de partidos</h2>
                <div class="mt-4 space-y-3">
                    @forelse($partidos as $partido)
                        <div class="rounded-2xl border border-neutral-200 p-4 dark:border-neutral-800">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-medium">{{ $partido->equipo1->name }} vs {{ $partido->equipo2->name }}</p>
                                    <p class="text-sm text-neutral-500">Torneo: {{ $partido->torneo->name }}</p>
                                    <p class="text-sm text-neutral-500">Fecha: {{ $partido->fecha_hora->format('Y-m-d H:i') }}</p>
                                    <p class="text-sm text-neutral-500">Resultado: {{ $partido->equipo1_puntaje ?? '-'}} - {{ $partido->equipo2_puntaje ?? '-' }}</p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                    {{ $partido->fecha_hora->isFuture() ? 'Pendiente' : 'En curso / terminado' }}
                                </span>
                            </div>

                            <form action="{{ route('partidos.update', $partido) }}" method="post" class="mt-4 space-y-4">
                                @csrf
                                @method('PATCH')

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Equipo 1</label>
                                        <select name="equipo1_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                                            @foreach($equipos as $equipo)
                                                <option value="{{ $equipo->id }}" @selected($equipo->id === $partido->equipo1_id)>{{ $equipo->flag }} {{ $equipo->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Equipo 2</label>
                                        <select name="equipo2_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                                            @foreach($equipos as $equipo)
                                                <option value="{{ $equipo->id }}" @selected($equipo->id === $partido->equipo2_id)>{{ $equipo->flag }} {{ $equipo->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Fecha y hora</label>
                                        <input type="datetime-local" name="fecha_hora" value="{{ $partido->fecha_hora->format('Y-m-d\TH:i') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Torneo</label>
                                        <select name="torneo_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                                            @foreach($torneos as $torneo)
                                                <option value="{{ $torneo->id }}" @selected($torneo->id === $partido->torneo_id)>{{ $torneo->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Puntaje equipo 1</label>
                                        <input type="number" name="equipo1_puntaje" min="0" value="{{ $partido->equipo1_puntaje }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Puntaje equipo 2</label>
                                        <input type="number" name="equipo2_puntaje" min="0" value="{{ $partido->equipo2_puntaje }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Estado</label>
                                    <input type="text" name="estado" value="{{ $partido->estado }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                                </div>

                                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Actualizar partido</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">No hay partidos registrados.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-layouts::app>
