<div class="min-h-screen px-4 py-8 sm:px-6">
    <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-4xl flex-col">
        <header class="mb-5 flex items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-sky-500 font-bold text-white shadow-lg shadow-sky-500/20">
                        AI
                    </div>

                    <div>
                        <h1 class="text-xl font-semibold text-white">
                            ShopMate AI
                        </h1>

                        <p class="text-sm text-slate-400">
                            Asistente de tecnología
                        </p>
                    </div>
                </div>
            </div>

            <button type="button" wire:click="newConversation"
                class="rounded-xl border border-slate-800 bg-slate-900 px-4 py-2 text-sm font-medium text-slate-300 transition hover:border-slate-700 hover:bg-slate-800">
                Nueva conversación
            </button>
        </header>

        <div class="mb-4 flex flex-wrap gap-2">
            <span class="rounded-full border border-slate-800 bg-slate-900 px-3 py-1 text-xs text-slate-400">
                Laravel AI SDK v1
            </span>

            <span class="rounded-full border border-slate-800 bg-slate-900 px-3 py-1 text-xs text-slate-400">
                OpenAI
            </span>

            <span class="rounded-full border border-slate-800 bg-slate-900 px-3 py-1 text-xs text-slate-400">
                Memoria MySQL
            </span>
        </div>

        <main
            class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-3xl border border-slate-800 bg-slate-950 shadow-2xl">
            <div class="flex-1 space-y-4 overflow-y-auto p-5 sm:p-7">
                @if (count($messages) === 0)
                <div class="flex h-full min-h-[420px] items-center justify-center">
                    <div class="max-w-xl text-center">
                        <div
                            class="mx-auto mb-5 flex size-16 items-center justify-center rounded-2xl bg-sky-500/10 text-2xl">
                            ✦
                        </div>

                        <h2 class="text-2xl font-semibold text-white">
                            ¿Qué estás buscando?
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-slate-400">
                            Puedo consultar productos,
                            disponibilidad y políticas de
                            nuestra tienda de demostración.
                        </p>

                        <div class="mt-6 grid gap-2 text-left sm:grid-cols-2">
                            <button type="button"
                                wire:click="$set('message', 'Necesito un mouse para trabajar y tengo máximo 60 dólares.')"
                                class="rounded-2xl border border-slate-800 bg-slate-900 p-4 text-sm text-slate-300 transition hover:border-sky-500/40 hover:bg-slate-800">
                                Mouse por menos de $60
                            </button>

                            <button type="button" wire:click="$set('message', '¿Cuál es la política de devoluciones?')"
                                class="rounded-2xl border border-slate-800 bg-slate-900 p-4 text-sm text-slate-300 transition hover:border-sky-500/40 hover:bg-slate-800">
                                Política de devoluciones
                            </button>
                        </div>
                    </div>
                </div>
                @else
                @foreach ($messages as $chatMessage)
                <x-chat.message :role="$chatMessage['role']" :content="$chatMessage['content']" />
                @endforeach
                @endif

                <div wire:loading wire:target="send" class="justify-start">
                    <div
                        class="inline-flex items-center gap-2 rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-sm text-slate-400">
                        <span class="size-2 animate-pulse rounded-full bg-sky-400"></span>

                        ShopMate está pensando...
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 bg-slate-950 p-4 sm:p-5">
                @if ($error)
                <div class="mb-3 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                    {{ $error }}
                </div>
                @endif

                <form wire:submit="send" class="flex items-end gap-3">
                    <div class="flex-1">
                        <label for="message" class="sr-only">
                            Mensaje
                        </label>

                        <textarea id="message" wire:model="message" rows="2" maxlength="1000"
                            placeholder="Pregúntame por productos, precios o políticas..."
                            class="block w-full resize-none rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-500 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10"></textarea>

                        @error('message')
                        <p class="mt-2 text-xs text-red-400">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-400 disabled:cursor-not-allowed disabled:opacity-50">
                        <span wire:loading.remove wire:target="send">
                            Enviar
                        </span>

                        <span wire:loading wire:target="send">
                            ...
                        </span>
                    </button>
                </form>

                <p class="mt-3 text-center text-[11px] text-slate-600">
                    Demo educativa. Los productos y políticas son datos ficticios.
                </p>
            </div>
        </main>
    </div>
</div>