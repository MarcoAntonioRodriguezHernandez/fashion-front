<x-layouts.master-layout title="Características" cardTitle="Características">

    <style>
        .icono {
            width: 20px;
            height: 20px;
            transition: width 0.3s ease, height 0.3s ease;
            color: #322C2B;
        }

        .icono:hover {
            width: 23px;
            height: 23px;
            color: #ADA8A8;
            cursor: pointer;
        }
    </style>

    <div class="d-flex justify-content-end mb-4">
        @permission(ModuleAliases::CHARACTERISTIC, PermissionTypes::CREATE)
        <a class="btn btn-primary" href="{{ route('base.characteristics.create.view') }}">
            <span class="indicator-label">Agregar Características</span>
        </a>
        @endpermission
    </div>

    <!--begin::characteristics-->
        <!--begin::Card body-->
        <div class="col-12">
            <div class="container">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
                    @foreach ($data as $characteristic)
                        <div class="col d-flex mb-4">
                            <div class="card col-12 mb-xl-8 flex-grow-1">
                                <div class="card-body">
                                    <h2 class="card-title d-flex justify-content-between">
                                        <b style="font-size: 18px;">{{ $characteristic->name }}</b>
                                        <div class="d-flex">
                                            <div class="d-flex justify-content-center mb-3">
                                                <div class="col-sm-2 mx-2">
                                                    <a
                                                        href="{{ route('base.characteristics.show', ['id' => $characteristic->id]) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" fill="currentColor" class="bi bi-eye icono"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                            <path
                                                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                @permission(ModuleAliases::CHARACTERISTIC, PermissionTypes::UPDATE)
                                                <div class="col-sm-2 mx-2">
                                                    <a
                                                        href="{{ route('base.characteristics.edit.view', $characteristic['id']) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" fill="currentColor"
                                                            class="bi bi-pencil-square icono" viewBox="0 0 16 16">
                                                            <path
                                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                            <path fill-rule="evenodd"
                                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="col-sm-2 mx-2">
                                                    <a onclick="eliminar({{ $characteristic->id }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" fill="currentColor"
                                                            class="bi bi-trash3-fill icono" viewBox="0 0 16 16">
                                                            <path
                                                                d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                @endpermission
                                            </div>
                                        </div>
                                    </h2>
                                    <div class="col md-4-12">
                                        <h6 class="fs-4 fw-semibold text-gray-500 mb-7">Filtros</h6>
                                        <div class="row row-cols-1 row-cols-md-2">
                                            @forelse ($characteristic->children as $char)
                                                <div class="col mb-1">
                                                    <div class="d-flex align-items-center">
                                                        <div class="col-md-12">
                                                            <p
                                                                class="badge py-3 px-4 fs-7 badge-light-dark bg-gray-200 text-wrap w-100 d-flex align-items-center justify-content-center text-center">
                                                                {{ $char->name }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="fw-bold w-100 text-center">No hay filtros en este apartado</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col my-5">
            {{ $data->links() }}
        </div>
        <!--end::Card body-->
    <!--end::Products-->
    <x-slot name="js">
        <x-sweet-alert 
        title="¿Estas seguro que deseas eliminar esta caracteristica?\n\nTambién se eliminarán todos los filtros relacionados"
        route="{{ route('base.characteristics.delete', '') }}"
        successTitle="La Característica ha sido eliminada"
        successText="La Característica ha sido eliminada exitosamente"
        errorTitle="Error al eliminar La caracteristica"
        errorText="Error al eliminar, intenta nuevamente"
        />
    </x-slot>

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>