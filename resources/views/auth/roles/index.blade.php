<x-layouts.master-layout title="Roles" cardTitle="Roles">

    <div class="col-12">
        <div class="container">
            <div class="mb-4 text-end">
                <a href="{{ route('auth.roles.create.view') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Agregar Rol
                </a>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
                @foreach ($data as $role)
                    <div class="col-md-4 mb-4">
                        <div class="card card-flush h-md-100">
                            <div class="card-body">
                                <h2 class="card-title d-flex justify-content-between">
                                    <b style="font-size: 18px;">
                                        <a href="{{ route('auth.roles.show', $role->id) }}">{{ $role->name }}</a>
                                    </b>
                                    <div class="d-flex">
                                        <div class="d-flex justify-content-center">
                                            <div class="col-sm-2 mx-2">
                                                <div class="d-flex">
                                                    <form style="visibility: hidden" id="eliminar-form-{{ $role->id }}"
                                                        action="{{ route('auth.roles.delete', $role->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="mx-1"
                                                            style="all: unset;"></button>
                                                    </form>
                                                    <a class="menu-link px-1 slide-top" onclick="confirmDestroy({{ $role->id }})"
                                                        style="cursor: pointer;">
                                                        <svg width="20" height="20" viewBox="0 0 18 18"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M15.375 4.5H2.625M14.1248 6.375L13.7798 11.55C13.647 13.5405 13.581 14.5358 12.9323 15.1425C12.2835 15.75 11.2853 15.75 9.29025 15.75H8.70975C6.71475 15.75 5.7165 15.75 5.06775 15.1425C4.419 14.5358 4.35225 13.5405 4.22025 11.55L3.87525 6.375M7.125 8.25L7.5 12M10.875 8.25L10.5 12"
                                                                stroke="#ED1010" stroke-width="1.5"
                                                                stroke-linecap="round" />
                                                            <path
                                                                d="M4.875 4.5H4.9575C5.25933 4.49229 5.55182 4.39367 5.79669 4.21703C6.04157 4.0404 6.22744 3.79398 6.33 3.51L6.3555 3.43275L6.42825 3.2145C6.4905 3.02775 6.522 2.93475 6.56325 2.85525C6.64441 2.69954 6.76088 2.56499 6.90336 2.46237C7.04583 2.35974 7.21035 2.2919 7.38375 2.26425C7.4715 2.25 7.56975 2.25 7.76625 2.25H10.2338C10.4303 2.25 10.5285 2.25 10.6162 2.26425C10.7896 2.2919 10.9542 2.35974 11.0966 2.46237C11.2391 2.56499 11.3556 2.69954 11.4367 2.85525C11.478 2.93475 11.5095 3.02775 11.5717 3.2145L11.6445 3.43275C11.7395 3.7487 11.9361 4.02451 12.2038 4.21745C12.4714 4.41039 12.7952 4.5097 13.125 4.5"
                                                                stroke="#ED1010" stroke-width="1.5" />
                                                        </svg>
                                                    </a>
                                                    <a class="mx-1 slide-top"
                                                        href="{{ route('auth.roles.edit.view', $role->id) }}">
                                                        <svg width="20" height="20" viewBox="0 0 16 16"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M11.2728 2.98294L13.0171 4.72637M13.3531 10.8823V13.3529C13.3531 13.7898 13.1795 14.2087 12.8706 14.5176C12.5618 14.8265 12.1428 15 11.706 15H2.64708C2.21024 15 1.7913 14.8265 1.48242 14.5176C1.17353 14.2087 1 13.7898 1 13.3529V4.29401C1 3.85718 1.17353 3.43824 1.48242 3.12935C1.7913 2.82046 2.21024 2.64693 2.64708 2.64693H5.11769M12.3945 1.44704L7.67807 6.16344C7.43437 6.40679 7.26817 6.71684 7.20042 7.05451L6.76476 9.23524L8.94549 8.79876C9.28314 8.73123 9.59279 8.5657 9.83656 8.32193L14.553 3.60553C14.6947 3.4638 14.8071 3.29555 14.8838 3.11037C14.9605 2.92519 15 2.72672 15 2.52628C15 2.32585 14.9605 2.12738 14.8838 1.9422C14.8071 1.75702 14.6947 1.58877 14.553 1.44704C14.4112 1.30531 14.243 1.19288 14.0578 1.11618C13.8726 1.03948 13.6741 1 13.4737 1C13.2733 1 13.0748 1.03948 12.8896 1.11618C12.7045 1.19288 12.5362 1.30531 12.3945 1.44704Z"
                                                                stroke="#5DCA29" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </h2>
                                <!--begin::Users-->
                                <div class="fs-6 fw-bold text-gray-700 mb-5">{{ $role->description }}</div>
                                <div class="fw-bold text-gray-600 mb-5">
                                    Total de permisos para este rol: {{ $role->permissions()->count() }}
                                </div>
                                <!--end::Users-->
                                <!--begin::Permissions-->
                                <div class="d-flex flex-column text-gray-600 overflow-auto">
                                    @forelse ($role->permissions as $permission)
                                        @if ($loop->index < 5)
                                            <div class="d-flex justify-content-between py-2 overflow-visible">
                                                <div>
                                                    <span
                                                        class="bullet bg-primary me-3"></span>{{ $permission->module->name }}
                                                </div>
                                                <div>
                                                    @if ($permission->read || $permission->update || $permission->create)
                                                        @if ($permission->read)
                                                            <span class="badge badge-roleReading fs-7 m-1"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-custom-class="tooltip-inverse"
                                                                data-bs-placement="top" title="Lectura">
                                                                <i class="ki-duotone ki-eye text-dark ">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                </i>
                                                            </span>
                                                        @endif
                                                        @if ($permission->update)
                                                            <span class="badge badge-roleWriting fs-7 m-1"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-custom-class="tooltip-inverse"
                                                                data-bs-placement="top" title="Escritura">
                                                                <i class="ki-duotone ki-pencil text-dark ">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </span>
                                                        @endif
                                                        @if ($permission->create)
                                                            <span class="badge badge-roleCreation fs-7 m-1"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-custom-class="tooltip-inverse"
                                                                data-bs-placement="top" title="Creación">
                                                                <i class="ki-duotone ki-plus-circle text-dark ">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span data-bs-toggle="tooltip"
                                                            data-bs-custom-class="tooltip-inverse"
                                                            data-bs-placement="top" title="Ninguno"
                                                            class="badge badge-roleNone text-dark fs-7 m-1">
                                                            <i class="ki-duotone ki-cross-circle text-dark">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class='d-flex align-items-center py-2'>
                                                <span class='bullet bg-primary me-3'></span>
                                                <em> y {{ $role->permissions()->count() - 5 }} más...</em>
                                            </div>
                                        @break
                                    @endif
                                @empty
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col my-5">
        {{ $data->links() }}
    </div>
</div>
<style>
.slide-top {
    transition: transform 0.1s ease; /* Agregar una transición para un efecto suave */
}

.slide-top:hover {
    animation: slide-top 0.1s linear both;
}

@keyframes slide-top {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(-5px);
    }
}

/* Agregar una animación para el regreso */
.slide-top:not(:hover) {
    animation: return-top 0.1s linear both;
}

@keyframes return-top {
    0% {
        transform: translateY(-5px);
    }
    100% {
        transform: translateY(0);
    }
}
</style>
<script>
    function confirmDestroy(id) {
        swal.fire({
            title: '¿Estás seguro que deseas eliminar este rol?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('#eliminar-form-' + id).submit();
            }
        });
    }
</script>
</x-layouts.master-layout>
