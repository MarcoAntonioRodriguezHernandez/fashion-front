<x-layouts.master-layout title=" Tranferencia de Distribuciones" cardTitle=" Tranferencia de Distribuciones">

    <x-layouts.card-header :createRoute="route('base.supply_transfer.create.view')" :createText="__('Agregar Suministros')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Receptor</th>
                        <th class="min-w-125px">Origen</th>
                        <th class="min-w-150px">Código de la distribución</th>
                        <th class="min-w-125px">Fecha de Recepción</th>
                        <th class="min-w-125px">Destino</th>
                        <th class="min-w-125px">Status</th>
                        @permission(ModuleAliases::USER, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $supplyTrans)
                        <tr>
                            <td class="min-w-125px">
                                <a href="{{ route('base.supply_transfer.show', $supplyTrans->id) }}"
                                    class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    data-kt-ecommerce-product-filter="product_id">{{ $supplyTrans['id'] }}</a>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $supplyTrans->recipient?->full_name ?? '<< No asignado >>' }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $supplyTrans->origin->name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $supplyTrans->supply->code }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $supplyTrans->reception_date ?? '<< No se ha recibido >>' }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $supplyTrans->destination->name }}</span>
                            </td>
                            <td class="min-w-125px">
                                @if ($supplyTrans->isDelivered)
                                    <div class="badge badge-light-success fw-bold">Entregado</div>
                                @else
                                    <div class="badge badge-light-danger fw-bold">No Entregado</div>
                                @endif
                            </td>
                            <x-actions-btn routeEdit="{{ route('base.supply_transfer.edit.view', $supplyTrans->id) }}"
                                onclickDelete="eliminar({{ $supplyTrans->id }})" :module="ModuleAliases::USER"/>
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar esta distribución?"
            route="{{ route('base.supply_transfer.delete', '') }}" successTitle="La distribución ha sido eliminado"
            successText="La distribución ha sido eliminada exitosamente" errorTitle="Error al eliminar la distribución"
            errorText="Error al eliminar la distribución, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
