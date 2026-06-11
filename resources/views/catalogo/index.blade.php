<x-layouts::app :title="'Catálogo'">
<h1>CATÁLOGO FUNCIONA</h1>
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16 text-center">
        <h1 class="text-4xl font-bold mb-2">Bienvenido a nuestra tienda</h1>
        <p class="text-indigo-200">Productos disponibles</p>
    </section>

    @livewire('catalogo-component')

</x-layouts::app>