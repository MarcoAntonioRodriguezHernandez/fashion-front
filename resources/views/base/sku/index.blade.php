<x-layouts.master-layout title="Skus" cardTitle="Skus">

    <x-layouts.card-header :module="ModuleAliases::SKU" :createRoute="route('base.sku.create.view')" :createText="__('Agregar Sku')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="max-w-125px">Código de referencia</th>
                        <th class="min-w-125px">Nombre</th>
                        <th class="min-w-150px">Descripción</th>
                        <th class="min-w-125px">Duración</th>
                        <th class="min-w-125px">Precio</th>
                        @permission(ModuleAliases::SKU, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $sku)
                        <tr>
                            <td><a href="{{ route('base.sku.show', $sku['id']) }}"
                                    class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    data-kt-ecommerce-product-filter="product_id">
                                    {{ $sku->id }}
                                </a></td>
                            <td>
                                <span class="fw-bold">{{ $sku->sku }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $sku['name'] }}</span>
                            <td>
                                <span class="fw-bold">{{ $sku['description'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $sku['duration'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $sku['price'] }}</span>
                            </td>
                             @permission(ModuleAliases::SKU, PermissionTypes::UPDATE)
                            <x-delete-btn onclickDelete="eliminar({{ $sku->id }})" />
                            @endpermission
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
        title="¿Estas seguro que deseas eliminar este sku?"
        route="{{ route('base.sku.delete', '') }}"
        successTitle="El sku ha sido eliminado"
        successText="El sku ha sido eliminado exitosamente"
        errorTitle="Error al eliminar el sku"
        errorText="Error al eliminar, intenta nuevamente"
        />
    </x-slot>

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>