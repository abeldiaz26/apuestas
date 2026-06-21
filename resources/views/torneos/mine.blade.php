<x-layouts::app :title="__('Mis Torneos')">
    <div class="space-y-6">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <h1 class="text-xl font-semibold">Mis Torneos</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">Torneos en los que estás habilitado y sus próximos partidos.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            @forelse($torneos as $torneo)
                @php
                    $stats = $torneoStats[$torneo->id] ?? null;
                    $betCount = $torneo->partidos->filter(fn ($partido) => $partido->apuestas->isNotEmpty())->count();
                @endphp

                <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold">{{ $torneo->name }}</h2>
                            <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">Resumen de puntos y tus apuestas en este torneo.</p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3">
                            <div class="rounded-3xl border border-neutral-200 bg-slate-50 p-4 text-center dark:border-neutral-800 dark:bg-slate-900">
                                <p class="text-xs uppercase tracking-[0.2em] text-neutral-500 dark:text-neutral-400">Tus puntos</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['user_total'] ?? 0) }}</p>
                            </div>
                            <div class="rounded-3xl border border-neutral-200 bg-slate-50 p-4 text-center dark:border-neutral-800 dark:bg-slate-900">
                                <p class="text-xs uppercase tracking-[0.2em] text-neutral-500 dark:text-neutral-400">Posición</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $stats['user_rank'] ?? '—' }}</p>
                            </div>
                            <div class="rounded-3xl border border-neutral-200 bg-slate-50 p-4 text-center dark:border-neutral-800 dark:bg-slate-900">
                                <p class="text-xs uppercase tracking-[0.2em] text-neutral-500 dark:text-neutral-400">Apuestas hechas</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $betCount }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                        <div class="space-y-4">
                            <div class="rounded-3xl border border-neutral-200 bg-white p-5 dark:border-neutral-800 dark:bg-neutral-950">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-base font-semibold">Ranking</h3>
                                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Top usuarios por puntos en este torneo.</p>
                                    </div>
                                </div>

                                @if($stats && $stats['rankings']->isNotEmpty())
                                    <div class="mt-5 overflow-hidden rounded-3xl border border-neutral-200 dark:border-neutral-800">
                                        <table class="min-w-full text-sm text-left">
                                            <thead class="bg-neutral-50 text-xs uppercase tracking-[0.24em] text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                                                <tr>
                                                    <th class="px-4 py-3">Lugar</th>
                                                    <th class="px-4 py-3">Usuario</th>
                                                    <th class="px-4 py-3">Puntos</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                                                @foreach($stats['rankings'] as $index => $row)
                                                    <tr class="{{ $row->user_id === auth()->id() ? 'bg-slate-50 dark:bg-slate-900' : '' }}">
                                                        <td class="px-4 py-3 font-semibold text-slate-700 dark:text-slate-200">{{ $index + 1 }}</td>
                                                        <td class="px-4 py-3 text-slate-600 dark:text-neutral-300">{{ $row->user->name ?? 'Usuario' }}</td>
                                                        <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ number_format($row->total_puntos) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="mt-5 rounded-3xl border border-dashed border-neutral-200 bg-slate-50 p-5 text-sm text-neutral-500 dark:border-neutral-800 dark:bg-slate-900 dark:text-neutral-400">
                                        Aún no hay puntos registrados en este torneo.
                                    </div>
                                @endif
                            </div>

                            <div class="rounded-3xl border border-neutral-200 bg-white p-5 dark:border-neutral-800 dark:bg-neutral-950">
                                <h3 class="text-base font-semibold">Tus apuestas recientes</h3>
                                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Apuestas sobre los próximos partidos del torneo.</p>
                                <div class="mt-4 space-y-3">
                                    @forelse($torneo->partidos as $partido)
                                        @php $apuesta = $partido->apuestas->first() ?? null; @endphp

                                        @if($apuesta)
                                            <div class="rounded-3xl border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-900">
                                                <div class="flex items-center justify-between gap-4">
                                                    <div>
                                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $partido->equipo1->name ?? 'Equipo 1' }} vs {{ $partido->equipo2->name ?? 'Equipo 2' }}</p>
                                                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">{{ $partido->fecha_hora->format('d M Y H:i') }}</p>
                                                    </div>
                                                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200">{{ $apuesta->equipo1_puntaje }} - {{ $apuesta->equipo2_puntaje }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <p class="text-sm text-neutral-500 dark:text-neutral-400">No tienes apuestas registradas todavía.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="rounded-3xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                                <h3 class="text-base font-semibold">Próximos partidos</h3>
                                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Apuesta o edita tu pronóstico antes de que empiece el partido.</p>
                                <div class="mt-4 space-y-3">
                                    @forelse($torneo->partidos as $partido)
                                        <div class="rounded-3xl border border-neutral-200 p-4 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
                                            <div class="flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center gap-2">
                                                        @if(!empty($partido->equipo1->flag))
                                                            <img src="{{ $partido->equipo1->flag }}" alt="{{ $partido->equipo1->name }} flag" class="w-8 h-6 object-cover rounded-sm" loading="lazy" onerror="this.style.display='none'" />
                                                        @endif
                                                        <span class="font-medium text-sm dark:text-white">{{ $partido->equipo1->name ?? '-' }}</span>
                                                    </div>

                                                    <span class="text-sm text-neutral-400 dark:text-neutral-400">vs</span>

                                                    <div class="flex items-center gap-2">
                                                        @if(!empty($partido->equipo2->flag))
                                                            <img src="{{ $partido->equipo2->flag }}" alt="{{ $partido->equipo2->name }} flag" class="w-8 h-6 object-cover rounded-sm" loading="lazy" onerror="this.style.display='none'" />
                                                        @endif
                                                        <span class="font-medium text-sm dark:text-white">{{ $partido->equipo2->name ?? '-' }}</span>
                                                    </div>
                                                </div>

                                                <div class="text-right">
                                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-neutral-100 dark:bg-neutral-800 text-sm text-neutral-600 dark:text-neutral-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500 dark:text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        <span>{{ $partido->fecha_hora->format('Y-m-d H:i') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            @php
                                                $apuesta = $partido->apuestas->first() ?? null;
                                            @endphp

                                            <div class="mt-3">
                                                @if($apuesta)
                                                    @if($partido->hasStarted())
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <p class="text-sm text-neutral-800 dark:text-neutral-100">Tu apuesta</p>
                                                                <p class="text-lg font-semibold dark:text-white">{{ $apuesta->equipo1_puntaje }} <span class="text-neutral-500 dark:text-neutral-400">:</span> {{ $apuesta->equipo2_puntaje }}</p>
                                                                <p class="text-sm text-neutral-500">Puntos: {{ $apuesta->puntos }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="rounded-md bg-red-50 text-red-700 px-3 py-2 text-sm dark:bg-red-900/10 dark:text-red-200">No editable</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <form action="{{ route('apuestas.update', $apuesta) }}" method="post" class="mt-2 grid gap-3 sm:grid-cols-3">
                                                            @csrf
                                                            @method('PATCH')

                                                            <div>
                                                                <label class="block text-xs text-neutral-600 dark:text-neutral-400">{{ $partido->equipo1->name ?? 'Equipo 1' }}</label>
                                                                <input type="number" name="equipo1_puntaje" min="0" value="{{ $apuesta->equipo1_puntaje }}" class="mt-1 block w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" required />
                                                            </div>

                                                            <div>
                                                                <label class="block text-xs text-neutral-600 dark:text-neutral-400">{{ $partido->equipo2->name ?? 'Equipo 2' }}</label>
                                                                <input type="number" name="equipo2_puntaje" min="0" value="{{ $apuesta->equipo2_puntaje }}" class="mt-1 block w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" required />
                                                            </div>

                                                            <div class="flex items-end">
                                                                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Actualizar apuesta</button>
                                                            </div>
                                                        </form>
                                                    @endif
                                                @else
                                                    @if($partido->hasStarted())
                                                        <p class="mt-2 text-sm text-neutral-500">No pudiste apostar: el partido ya empezó.</p>
                                                    @else
                                                        <form action="{{ route('apuestas.store') }}" method="post" class="mt-2 grid gap-3 sm:grid-cols-3">
                                                            @csrf
                                                            <input type="hidden" name="partido_id" value="{{ $partido->id }}" />

                                                            <div>
                                                                <label class="block text-xs text-neutral-600 dark:text-neutral-400">{{ $partido->equipo1->name ?? 'Equipo 1' }}</label>
                                                                <input type="number" name="equipo1_puntaje" min="0" value="0" class="mt-1 block w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" required />
                                                            </div>

                                                            <div>
                                                                <label class="block text-xs text-neutral-600 dark:text-neutral-400">{{ $partido->equipo2->name ?? 'Equipo 2' }}</label>
                                                                <input type="number" name="equipo2_puntaje" min="0" value="0" class="mt-1 block w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" required />
                                                            </div>

                                                            <div class="flex items-end">
                                                                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Apostar</button>
                                                            </div>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-neutral-500">No hay partidos próximos en este torneo.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @empty
                <p class="text-sm text-neutral-500">No estás habilitado en ningún torneo.</p>
            @endforelse
        </div>
    </div>
</x-layouts::app>
