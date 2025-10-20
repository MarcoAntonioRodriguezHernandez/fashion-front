<x-layouts.master-layout title="Proveedores" cardTitle="Proveedores">

    <x-layouts.card-header :module="ModuleAliases::PROVIDER" :createRoute="route('base.provider.create.view')" :createText="__('Agregar proveedor')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Nombre</th>
                        <th class="min-w-125px">Contacto</th>
                        <th class="min-w-125px">Correo Electrónico</th>
                        <th class="min-w-125px">Teléfono</th>
                        <th class="min-w-125px">Cantidad de Marcas</th>
                        @permission(ModuleAliases::PROVIDER, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $provider)
                        <tr>
                            <td>{{ $provider['id'] }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="ms-5">
                                        <!--begin::Title-->
                                        <a href="{{ route('base.provider.show', $provider['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">{{ $provider['name'] }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $provider['contact'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $provider['email'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $provider['phone'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ trans_choice(':value marca asociada|:value marcas asociadas', $provider['designers']->count(), ['value' => $provider['designers']->count()]) }}</span>
                            </td>
                            <x-actions-btn :module="ModuleAliases::PROVIDER" routeEdit="{{ route('base.provider.edit.view', $provider['id']) }}" onclickDelete="eliminar({{ $provider->id }})" />
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <div class="col my-5">
            {{ $data->links() }}
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Products-->
    <x-slot name="js">
        <x-sweet-alert 
        title="¿Estas seguro que deseas eliminar este proveedor?"
        route="{{ route('base.provider.delete', '') }}"
        successTitle="El proveedor ha sido eliminado"
        successText="El proveedor ha sido eliminado exitosamente"
        errorTitle="Error al eliminar el proveedor"
        errorText="Error al eliminar, intenta nuevamente"
        />
    </x-slot>

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>
