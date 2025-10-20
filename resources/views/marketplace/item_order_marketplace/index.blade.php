<x-layouts.master-layout title="Órdenes de Artículos" cardTitle="Órdenes de Artículos">
    <x-layouts.card-header :createRoute="route('marketplace.item_order_marketplace.create.view')" :createText="__('Agregar Órdenes de Artículos')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Artículo</th>
                        <th class="min-w-125px">Código de Órden</th>
                        <th class="min-w-125px">Precio de Ajustes</th>
                        <th class="min-w-125px">Tipo de Venta</th>
                        <th class="min-w-125px">Estado</th>
                        <th class="min-w-125px text-start">Acciones</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $productOrder)
                        <tr>
                            <td>
                                <span class="fw-bold">
                                    <a class="fw-bold" href="{{ route('marketplace.item_order_marketplace.show', $productOrder->id) }}">{{ $productOrder->id }}</a>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $productOrder->item->barcode }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $productOrder->orderMarketplace->code }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $productOrder->fitting_price ?? 'Sin ajustes' }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $productOrder->sale_name }}</span>
                            </td>
                            <td class="min-w-125px">
                                @if ($productOrder->status == 1)
                                    <div class="badge badge-light-success fw-bold">{{ __('Activo') }}
                                    </div>
                                @else
                                    <div class="badge badge-light-danger fw-bold">{{ __('Inactivo') }}
                                    </div>
                                @endif
                            </td>
                            <x-actions-btn routeEdit="{{ route('marketplace.item_order_marketplace.edit.view', $productOrder->id) }}" onclickDelete="eliminar({{ $productOrder->id }})" />
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar esta órden de artículo?"
            route="{{ route('marketplace.item_order_marketplace.delete', '') }}"
            successTitle="La órden de artículo ha sido eliminada"
            successText="La órden de artículo ha sido eliminada exitosamente"
            errorTitle="Error al eliminar la órden de artículo"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
