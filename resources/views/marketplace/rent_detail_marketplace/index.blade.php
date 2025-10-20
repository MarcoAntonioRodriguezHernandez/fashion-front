<x-layouts.master-layout title="Mercado de detalles de alquiler" cardTitle="Mercado de detalles de alquiler">

    <x-layouts.card-header :createRoute="route('marketplace.rent_detail_marketplace.create.view')" :createText="__('Agregar mercado de alquiler')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Órden de Artículo</th>
                        <th class="min-w-125px">Fecha de Inicio</th>
                        <th class="min-w-125px">Fecha Final</th>
                        <th class="min-w-125px">Precio del Seguro</th>
                        <th class="min-w-125px">Descripción</th>
                        <th class="min-w-125px">Estado</th>
                        <th class="min-w-125px text-start">Acciones</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $item)
                        <tr>
                            <td>
                                <!--begin::Title-->
                                <a href="{{ route('marketplace.rent_detail_marketplace.show', $item['id']) }}"
                                    class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    data-kt-ecommerce-product-filter="product_name">{{ $item['id'] }}</a>
                                <!--end::Title-->
                            </td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="ms-5">
                                        {{ $item->itemOrderMarketplace->code }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $item['date_start'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $item['date_end'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $productOrder['insurance_price'] ?? 'Sin seguro' }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $item['description'] }}</span>
                            </td>
                            <td class="min-w-125px">
                                @if ($item['status'] == 1)
                                    <div class="badge badge-light-success fw-bold">{{ __('Activo') }}
                                    </div>
                                @else
                                    <div class="badge badge-light-danger fw-bold">{{ __('Inactivo') }}
                                    </div>
                                @endif
                            </td>
                            <x-actions-btn
                                routeEdit="{{ route('marketplace.rent_detail_marketplace.edit.view', $item['id']) }}"
                                onclickDelete="eliminar({{ $item->id }})" />
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar este detalle de alquiler de mercado?"
            route="{{ route('marketplace.rent_detail_marketplace.delete', '') }}"
            successTitle="El detalle de alquiler de mercado ha sido eliminado"
            successText="El detalle de alquiler de mercado ha sido eliminado exitosamente"
            errorTitle="Error al eliminar el detalle de alquiler de mercado"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
