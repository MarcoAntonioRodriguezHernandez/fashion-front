<x-layouts.error-layout title="Acceso denegado">
    <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">
       PÁGINA NO ENCONTRADA
    </h1>
    <!--end::Title-->
    <!--begin::Text-->
    <div class="fw-semibold fs-6 text-gray-500 mb-7">
        La página solicitada no existe o no se encuentra disponible
    </div>
    <div class="fw-bold fs-6 text-gray-500">
        Error: 404
    </div>
    <!--end::Text-->
    <!--begin::Illustration-->
    <div class="mb-7">
        <img src="{{asset('media/auth/404-dark.png')}}" class="mw-100 mh-300px theme-light-show"
        alt="" />
    </div>
    <!--end::Illustration-->
    <!--begin::Link-->
    <div class="mt-3">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">
        Volver a inicio
        </a>
    </div>
</x-layouts.error-layout>