<x-layouts::app :title="__('Usuarios')">
    <div class="space-y-6">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <h1 class="text-xl font-semibold">Usuarios</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">Crea y administra usuarios y asigna los torneos en los que pueden apostar.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <h2 class="text-lg font-semibold">Nuevo usuario</h2>
                <form action="{{ route('usuarios.store') }}" method="post" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 px-4 py-2 text-sm" required />
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Código</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 px-4 py-2 text-sm" required />
                        @error('code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-2 block w-full rounded-xl border border-neutral-200 px-4 py-2 text-sm" />
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Rol</label>
                        <select name="role" class="mt-2 block w-full rounded-xl border border-neutral-200 px-4 py-2 text-sm">
                            <option value="user">Usuario</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Crear usuario</button>
                </form>
            </section>

            <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <h2 class="text-lg font-semibold">Lista de usuarios</h2>
                <div class="mt-4 space-y-3">
                    @forelse($users as $user)
                        <div class="rounded-2xl border border-neutral-200 p-4 dark:border-neutral-800">
                            <form action="{{ route('usuarios.update', $user) }}" method="post" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div class="grid gap-4 sm:grid-cols-3">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Nombre</label>
                                        <input type="text" name="name" value="{{ $user->name }}" class="mt-2 block w-full rounded-xl border border-neutral-200 px-4 py-2 text-sm" required />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Código</label>
                                        <input type="text" name="code" value="{{ $user->code }}" class="mt-2 block w-full rounded-xl border border-neutral-200 px-4 py-2 text-sm" required />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Rol</label>
                                        <select name="role" class="mt-2 block w-full rounded-xl border border-neutral-200 px-4 py-2 text-sm">
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Usuario</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Actualizar</button>
                            </form>

                            <form action="{{ route('usuarios.sync-tournaments', $user) }}" method="post" class="mt-4">
                                @csrf
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">Torneos habilitados</label>
                                <select name="torneos[]" multiple class="mt-2 block w-full rounded-xl border border-neutral-200 px-4 py-2 text-sm">
                                    @foreach($torneos as $torneo)
                                        <option value="{{ $torneo->id }}" {{ $user->tournaments->contains($torneo) ? 'selected' : '' }}>{{ $torneo->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="mt-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Guardar torneos</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">No hay usuarios registrados.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-layouts::app>
