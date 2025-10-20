<x-layouts.master-layout title="Factura" cardTitle="Facturas">

    <x-layouts.card-header :module="ModuleAliases::INVOICE" :createRoute="route('base.invoice.create.view')" :createText="__('Agregar facturas')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Número de factura</th>
                        <th class="min-w-125px">Cliente</th>
                        <th class="min-w-150px">Estado de pago</th>
                        <th class="min-w-125px">Fecha de emisión</th>
                        <th class="min-w-125px">Método de pago</th>
                        <th class="min-w-125px">Tipo de cambio</th>
                        @permission(ModuleAliases::INVOICE, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $invoice)
                        <tr>
                            <td>{{ $invoice['id'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="ms-5">
                                        <!--begin::Title-->
                                        <a href="{{ route('base.invoice.show', $invoice['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">{{ $invoice['invoice_number'] }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $invoice->user->full_name }}</span>
                            </td>
                            <td class="min-w-125px">
                                <div
                                    class="badge badge-light-{{ PaymentStatuses::getColor($invoice->payment_status) }} fw-bold">
                                    {{ PaymentStatuses::getName($invoice->payment_status) }}
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $invoice['issuance_date'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $invoice->paymentType->name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $invoice['exchange_rate'] }}</span>
                            </td>
                            <x-actions-btn :module="ModuleAliases::INVOICE" routeEdit="{{ route('base.invoice.edit.view', $invoice['id']) }}"
                                onclickDelete="eliminar({{ $invoice->id }})" />
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar esta factura?"
            route="{{ route('base.invoice.delete', '') }}" successTitle="La factura ha sido eliminado"
            successText="La factura ha sido eliminado exitosamente" errorTitle="Error al eliminar la factura"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
