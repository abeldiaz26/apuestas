<x-layouts::app :title="__('Apuestas')">
    <div class="space-y-6">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <h1 class="text-xl font-semibold">Apuestas</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">Agrega apuestas y calcula los puntos según el resultado del partido.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <h2 class="text-lg font-semibold">Nueva apuesta</h2>
                <form action="{{ route('apuestas.store') }}" method="post" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Partido</label>
                        <select name="partido_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                            <option value="">Seleccione un partido</option>
                            @foreach($partidos as $partido)
                                <option value="{{ $partido->id }}">{{ $partido->equipo1->name }} vs {{ $partido->equipo2->name }} ({{ $partido->fecha_hora->format('Y-m-d H:i') }})</option>
                            @endforeach
                        </select>
                        @error('partido_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Usuario</label>
                        <select name="user_id" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required>
                            <option value="">Seleccione un usuario</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->code }})</option>
                            @endforeach
                        </select>
                        @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Puntaje equipo 1</label>
                            <input type="number" name="equipo1_puntaje" min="0" value="{{ old('equipo1_puntaje') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                            @error('equipo1_puntaje')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Puntaje equipo 2</label>
                            <input type="number" name="equipo2_puntaje" min="0" value="{{ old('equipo2_puntaje') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                            @error('equipo2_puntaje')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Guardar</button>
                </form>
            </section>

            <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <h2 class="text-lg font-semibold">Lista de apuestas</h2>
                <div class="mt-4 space-y-3">
                    @forelse($apuestas as $apuesta)
                        <div class="rounded-2xl border border-neutral-200 p-4 dark:border-neutral-800">
                            <p class="font-medium">{{ $apuesta->user->name }} – {{ $apuesta->partido->equipo1->name }} {{ $apuesta->equipo1_puntaje }} : {{ $apuesta->equipo2_puntaje }} {{ $apuesta->partido->equipo2->name }}</p>
                            <p class="text-sm text-neutral-500">Puntos: {{ $apuesta->puntos }}</p>
                            <p class="text-sm text-neutral-500">Partido: {{ $apuesta->partido->fecha_hora->format('Y-m-d H:i') }}</p>

                            @if($apuesta->isEditable())
                                <form action="{{ route('apuestas.update', $apuesta) }}" method="post" class="mt-4 grid gap-4 sm:grid-cols-2">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Puntaje equipo 1</label>
                                        <input type="number" name="equipo1_puntaje" min="0" value="{{ $apuesta->equipo1_puntaje }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Puntaje equipo 2</label>
                                        <input type="number" name="equipo2_puntaje" min="0" value="{{ $apuesta->equipo2_puntaje }}" class="mt-2 block w-full rounded-xl border border-neutral-200 bg-transparent px-4 py-2 text-sm text-neutral-900 outline-none focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:text-white" required />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <button type="submit" class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">Actualizar apuesta</button>
                                    </div>
                                </form>
                            @else
                                <p class="mt-4 rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/10 dark:text-red-200">Esta apuesta ya no puede editarse: el partido ya comenzó.</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">No hay apuestas registradas.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-layouts::app>
