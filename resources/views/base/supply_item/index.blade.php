<x-layouts.master-layout title="Artículos en Distribución" cardTitle="Artículos en Distribuciones">

    <x-layouts.card-header :createRoute="route('base.supply_item.create.view')" :createText="__('Agregar Suministros')" />

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
                        <th class="min-w-150px">Entregado</th>
                        <th class="min-w-125px">Status</th>
                        <th class="min-w-125px">Notas</th>
                        <th class="min-w-125px text-start">Acciones</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $supply)
                        <tr>
                            <td class="min-w-125px">
                                <a href="{{ route('base.supply_item.show', $supply['id']) }}"
                                    class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    data-kt-ecommerce-product-filter="product_id">{{ $supply['id'] }}</a>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $supply->item->serial_number }}</span>
                            </td>
                            <td>
                                @if ($supply->delivered == 1)
                                    <div class="badge badge-light-success fw-bold">{{ __('Si') }}
                                    </div>
                                @else
                                    <div class="badge badge-light-danger fw-bold">{{ __('No') }}
                                    </div>
                                @endif
                            </td>
                            <td class="min-w-125px">
                                <div class="badge badge-light-{{SupplyStatuses::getColor($supply->status) }} fw-bold">
                                {{ SupplyStatuses::getName($supply->status) }}
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $supply->details }}</span>
                            </td>
                            <x-actions-btn routeEdit="{{ route('base.supply_item.edit.view', $supply['id']) }}"
                                onclickDelete="eliminar({{ $supply->id }})" />
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar este ejemplo?"
            route="{{ route('base.supply_item.delete', '') }}" successTitle="El ejemplo ha sido eliminado"
            successText="El ejemplo ha sido eliminado exitosamente" errorTitle="Error al eliminar el ejemplo"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
