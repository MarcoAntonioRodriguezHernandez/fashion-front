<x-layouts.master-layout title="Precios de envio" cardTitle="Precios de envio">

    <x-layouts.card-header :module="ModuleAliases::SHIPPING_PRICE" :createRoute="route('base.shipping_prices.create.view')" :createText="__('Agregar Precio de envio')" />

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
                        <th class="min-w-125px">Precio</th>
                        @permission(ModuleAliases::SHIPPING_PRICE, PermissionTypes::UPDATE)
                            <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $ShippingPrice)
                        <tr>
                            <td>{{ $ShippingPrice['id'] }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <!--begin::Thumbnail-->
                                    <a href="{{ route('base.shipping_prices.show', $ShippingPrice['id']) }}" class="symbol symbol-50px">
                                        <span class="fw-bold">{{ $ShippingPrice->name }}</span>
                                    </a>
                                    <!--end::Thumbnail-->
                                </div>
                            </td>
                            <td>{{ $ShippingPrice['code'] }}</td>
                            <td>$ {{ $ShippingPrice['price'] }}</td>
                            <x-actions-btn :module="ModuleAliases::SHIPPING_PRICE" routeEdit="{{ route('base.shipping_prices.edit.view', $ShippingPrice['id']) }}" onclickDelete="eliminar({{ $ShippingPrice->id }})" />
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
            title="¿Estas seguro que deseas eliminar este precio de envio?"
            route="{{ route('base.shipping_prices.delete', '') }}"
            successTitle="El precio de envio ha sido eliminado"
            successText="El precio de envio ha sido eliminado exitosamente"
            errorTitle="Error al eliminar el precio de envio"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
