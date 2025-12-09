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
                    <!-- Le bloc coloré (Vert/Bleu) -->
                    <div class="bg-gradient-to-r from-green-400 to-blue-500 rounded-lg shadow-lg p-6 text-black transform hover:scale-[1.01] transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="text-4xl bg-white/20 rounded-full p-3 backdrop-blur-sm">
                                ✅
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Projet Validé & Sécurisé</h3>
                                <p class="mt-1 opacity-90 font-medium">
                                    Tout est au vert, Chef. Docker ronronne comme un chaton. 
                                    La base de données est connectée. <br>
                                    <span class="font-bold text-yellow-300">Mission accomplie.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>