<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Ajout de 'text-gray-900' pour que le texte soit bien NOIR -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900">
                
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>

                <div class="p-6 bg-gray-50">
                    <!-- Le bloc coloré (Violet/Rose) -->
                    <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 rounded-lg shadow-lg p-6 text-black transform hover:scale-[1.01] transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="text-4xl bg-white/20 rounded-full p-3 backdrop-blur-sm animate-pulse">
                                😎
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Zone VIP - T'as vu ça ?</h3>
                                <p class="mt-1 opacity-90 font-medium">
                                    Non seulement je suis connecté, mais en plus j'ai du style.
                                    Le serveur 2 est debout et il brille. <br>
                                    <span class="font-bold underline decoration-wavy">C'était facile en fait (ou pas).</span> ✨
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>