<x-layouts.master-layout title="Tiendas" cardTitle="Tiendas">
    <!--begin::Content container-->
    <div class="row">
        <x-location-map :locations="$locations" header="Mapa de las Sucursales" title="Sucursales" description="Distribución de sucursales" />
    </div> 
    <x-layouts.card-header :createRoute="route('base.store.create.view')" :createText="__('Agregar Tiendas')" :module="ModuleAliases::STORE" :searchBy="$searchBy" />
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
                        <th class="min-w-125px">Código</th>
                        <th class="min-w-125px">Marketplace</th>
                        <th class="min-w-125px">Dirección</th>
                        <th class="min-w-125px">Tipo de tienda</th>
                        <th class="min-w-125px">Estado</th>
                        @permission(ModuleAliases::STORE, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $store)
                        <tr>
                            <td>{{ $store['id'] }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <!--begin::Title-->
                                        <a href="{{ route('base.store.show', $store['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">{{ $store['name'] }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $store['code'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $store->marketplace->name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $store['address'] }}, {{ $store['municipality'] }}, {{ $store['cp'] }}</span>
                            </td>
                            <td>
                                <span
                                    class="fw-bold">{{ $store['store_type'] == 1 ? 'Tienda' : 'Almacen', $store['store_type'] == 2 ? 'Almacen' : 'Tienda' }}</span>
                            </td>
                            <td class="min-w-125px">
                                <div class="badge badge-light-{{StoreStatuses::getColor($store->status) }} fw-bold">
                                {{ StoreStatuses::getName($store->status) }}
                                </div>
                            </td>
                            <x-actions-btn routeEdit="{{ route('base.store.edit.view', $store['id']) }}"
                                onclickDelete="eliminar({{ $store->id }})" :module="ModuleAliases::STORE">
                                <a class="menu-link px-2 pb-0" href="{{ route('base.supply.show_store', $store['id']) }}" style="cursor: pointer;">
                                    <i class="ki-duotone ki-delivery">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </a>
                            </x-actions-btn>
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar esta tienda?"
            route="{{ route('base.store.delete', '') }}" successTitle="La tienda ha sido eliminado"
            successText="La tienda ha sido eliminada exitosamente" errorTitle="Error al eliminar la tienda"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
