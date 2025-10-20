<x-layouts.master-layout title="Ordenes de pago del marketplace" cardTitle="Ordenes de pago del marketplace">

    <x-layouts.card-header :createRoute="route('marketplace.payment_order_marketplace.view')" :createText="__('Agregar ordenes de pago')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Orden de mercado</th>
                        <th class="min-w-125px">Total</th>
                        <th class="min-w-150px">Pago</th>
                        <th class="min-w-125px">Tipo de pago</th>
                        <th class="min-w-125px">Estado</th>
                        <th class="min-w-125px text-start">Acciones</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $paymentOrdermarketplace)
                        <tr>
                            <td>{{ $paymentOrdermarketplace['id'] }}</td>
                            <td class="fw-bold">
                                <a href="{{ route('marketplace.payment_order_marketplace.show', $paymentOrdermarketplace['id']) }}"
                                    class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    data-kt-ecommerce-product-filter="product_name">{{ $paymentOrdermarketplace->ordermarketplace->user->email }}</a>

                            </td>
                            <td>
                                <span class="fw-bold">{{ $paymentOrdermarketplace['total'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $paymentOrdermarketplace['payment'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $paymentOrdermarketplace->paymentTypeId->name }}</span>
                            </td>
                            <td class="min-w-125px">
                                @if ($paymentOrdermarketplace['status'] == 1)
                                    <div class="badge badge-light-success fw-bold">{{ __('Activo') }}
                                    </div>
                                @else
                                    <div class="badge badge-light-danger fw-bold">{{ __('Inactivo') }}
                                    </div>
                                @endif
                            </td>
                            <x-actions-btn
                                routeEdit="{{ route('payment_order_marketplace.edit.view', $paymentOrdermarketplace['id']) }}"
                                onclickDelete="eliminar({{ $paymentOrdermarketplace->id }})"
                                />
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
        <x-sweet-alert title="¿Estás seguro que deseas eliminar este producto del marketplace?"
            route="{{ route('payment_order_marketplace.delete', '') }}" successTitle="El producto se ha sido eliminado"
            successText="El producto se ha sido eliminado exitosamente" errorTitle="Error al eliminar el producto"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
