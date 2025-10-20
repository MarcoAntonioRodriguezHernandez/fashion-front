<x-layouts.master-layout title="Esquemas de precios" cardTitle="Esquemas de precios">

    <x-layouts.card-header :module="ModuleAliases::PRICING_SCHEME" :createRoute="route('base.pricing_schemes.create.view')" :createText="__('Agregar esquema de precios')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="max-w-125px">Renta 4 días</th>
                        <th class="min-w-125px">Renta 8 días</th>
                        <th class="min-w-125px">Categoría</th>
                        <th class="min-w-125px">MSRP</th>
                        <th class="min-w-125px">Incremento</th>
                        @permission(ModuleAliases::PRICING_SCHEME, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $pricing_scheme)
                        <tr>
                            <td>
                                <a href="{{ route('base.pricing_schemes.show', $pricing_scheme['id']) }}"
                                    class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    data-kt-ecommerce-product-filter="product_id">
                                    {{ $pricing_scheme->id }}
                                </a>
                            </td>
                            <td>
                                <span class="fw-bold">$ {{ $pricing_scheme->sku_4->price }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">$ {{ $pricing_scheme->sku_8->price }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $pricing_scheme->category->name}}</span>
                            </td>
                            <td>
                                <span class="fw-bold">$ {{ $pricing_scheme['msrp'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">% {{ $pricing_scheme['increase'] }}</span>
                            </td>
                            <x-actions-btn :module="ModuleAliases::PRICING_SCHEME" routeEdit="{{ route('base.pricing_schemes.edit.view', $pricing_scheme['id']) }}" onclickDelete="eliminar({{ $pricing_scheme->id }})" />
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
        title="¿Estas seguro que deseas eliminar este esquema de precios?"
        route="{{ route('base.pricing_schemes.delete', '') }}"
        successTitle="El esquema de precios ha sido eliminado"
        successText="El esquema de precios ha sido eliminado exitosamente"
        errorTitle="Error al eliminar el esquema de precios"
        errorText="Error al eliminar, intenta nuevamente"
        />
    </x-slot>

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>