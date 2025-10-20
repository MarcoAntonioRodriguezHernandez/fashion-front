<x-layouts.master-layout :title="$userType === 'employee' ? 'Empleados' : 'Clientes'" :cardTitle="$userType === 'employee' ? 'Empleados' : 'Clientes'">

    <form method="GET" action="{{ route('base.user.index', ['userType' => $userType]) }}"
        class="d-flex align-items-end gap-3" id="filterForm">
        @if ($userType === 'employee')
            <div>
                <label for="store" class="form-label fw-bold mb-0">Filtrar por sucursal:</label>
                <select name="store_id" id="store" class="form-select">
                    <option value="">Todas las sucursales</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="mx-3">
            <label for="per_page" class="form-label fw-bold mb-0">Mostrar:</label>
            <select name="per_page" id="per_page" class="form-select" style="width: 100px;"
                onchange="this.form.submit()">
                @foreach ([10, 25, 50] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    <x-layouts.card-header :module="ModuleAliases::USER" :createRoute="route('base.temporary.create')" :createText="__('Generar invitación de usuario')" :searchBy="$searchBy" :debounce="true"/>

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Foto de perfil</th>
                        <th class="min-w-125px">Nombre</th>
                        <th class="min-w-125px">Apellido</th>
                        <th class="min-w-150px">Email</th>
                        @if ($userType === 'employee')
                            <th class="min-w-125px">Tienda</th>
                        @endif
                        @permission(ModuleAliases::USER, PermissionTypes::UPDATE)
                            <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center justify-content-center">
                                    <!--begin::Thumbnail-->
                                    <a class="symbol symbol-50px">
                                        <span class="symbol-label"
                                            style="background-image: url('{{ asset($user->photo ?: 'src/img/user-image.png') }}');"></span>
                                    </a>
                                </div>
                            </td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <!--begin::Thumbnail-->
                                    <a href="{{ route('base.user.show', $user->id) }}" class="symbol symbol-50px">
                                    </a>
                                    <!--end::Thumbnail-->
                                    <div class="ms-5">
                                        <!--begin::Title-->
                                        <a href="{{ route('base.user.show', $user->id) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">{{ $user->name }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $user->last_name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $user->email }}</span>
                            </td>
                            @if ($userType === 'employee')
                                <td>
                                    <span
                                        class="fw-bold">{{ $user->employeeDetail?->store->name ?? 'Sin Datos' }}</span>
                                </td>
                            @endif
                            <x-actions-btn routeEdit="{{ route('base.user.edit.view', $user['id']) }}"
                                onclickDelete="eliminar({{ $user->id }})" :module="ModuleAliases::USER" />
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <div class="col my-5">
            {{ $data->appends(request()->query())->links() }}
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Products-->
    <x-slot name="js">
        <x-sweet-alert title="¿Estas seguro que deseas eliminar este usuario?"
            route="{{ route('base.user.delete', '') }}" successTitle="El usuario ha sido eliminado"
            successText="El usuario ha sido eliminado exitosamente" errorTitle="Error al eliminar el usuario"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
    <script>
        @if (isset($userType) && $userType === 'employee')
            document.getElementById('store').addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        @endif
    </script>
</x-layouts.master-layout>
