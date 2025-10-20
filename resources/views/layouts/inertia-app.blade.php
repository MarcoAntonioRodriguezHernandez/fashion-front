<x-layouts.master-layout :title="config('app.name')" :cardTitle="config('app.name')">
    <x-slot name="metas">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jkanban@1.3.1/dist/jkanban.min.css" integrity="sha256-0qQUTR+++z/x9FrRZQGS5mM6wQCGulXRHHej0Izs5Uk=" crossorigin="anonymous">
        @vite('resources/js/app.js')
        @inertiaHead
    </x-slot>

    @inertia

    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/jkanban@1.3.1/dist/jkanban.min.js" integrity="sha256-2zPz/e/xdgS0ezx2raBcPjhDvDG39IUNtGU2zQG6+ms=" crossorigin="anonymous"></script>
    </x-slot>
</x-layouts.master-layout>
