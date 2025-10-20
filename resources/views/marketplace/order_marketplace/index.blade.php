<x-layouts.master-layout title="Orden de Marketplace" cardTitle="Orden de Marketplace">

    <form method="GET" action="{{ route('marketplace.order_marketplace.index') }}" class="d-flex align-items-end gap-3"
        id="filterForm">
        <div>
            <label for="store" class="form-label fw-bold mb-0">Filtrar por sucursal:</label>
            <select name="store_id" id="store" class="form-select">
                <option value="">Todas las sucursales</option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="employee_id" class="form-label fw-bold mb-0">Filtrar por responsable:</label>
            <select name="employee_id" id="employee_id" class="form-select">
                <option value="">Todos los responsables</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} {{ $employee->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="created_at" class="form-label fw-bold mb-0">Filtrar por fecha de creación:</label>
            <input type="date" name="created_at" id="created_at" value="{{ request('created_at') }}"
                class="form-control">
        </div>

        <div>
            <label for="date_start" class="form-label fw-bold mb-0">Filtrar por fecha de renta:</label>
            <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}"
                class="form-control">
        </div>

        <div>
            <label for="per_page" class="form-label fw-bold mb-0">Mostrar:</label>
            <select name="per_page" id="per_page" class="form-select" style="width: 100px;">
                @foreach ([10, 25, 50] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="sale_type" class="form-label fw-bold mb-0">Filtrar por tipo:</label>
            <select name="sale_type" id="sale_type" class="form-select">
                <option value="">Todos</option>
                <option value="venta" {{ request('sale_type') === 'venta' ? 'selected' : '' }}>Venta</option>
                <option value="renta" {{ request('sale_type') === 'renta' ? 'selected' : '' }}>Renta</option>
                <option value="renta_venta" {{ request('sale_type') === 'renta_venta' ? 'selected' : '' }}>Venta/Renta
                </option>
            </select>
        </div>

        <input type="hidden" name="search" value="{{ request('search') }}" />

    </form>

    @php
        $isWarehouse = Auth::user()?->employeeDetail?->store?->name === 'Almacén';
    @endphp

    <x-layouts.card-header :createRoute="route('marketplace.order_marketplace.full_create.view')" :createText="__('Agregar Orden de Marketplace')" :searchBy="'search'" :showCreateButton="!$isWarehouse" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Codigo</th>
                        <th class="min-w-125px">Fecha</th>
                        <th class="min-w-125px">Cliente</th>
                        <th class="min-w-125px">Responsable</th>
                        <th class="min-w-125px">Tienda</th>
                        <th class="min-w-125px">Cantidad total</th>
                        <th class="min-w-125px">Tipo</th>
                        <th class="min-w-125px">Estado</th>
                        <th class="min-w-125px text-start">Acciones</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $orderMarketplace)
                        <tr>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="ms-5">
                                        <a href="{{ route('marketplace.order_marketplace.show', $orderMarketplace->id) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold barcode-hover"
                                            data-kt-ecommerce-product-filter="product_name"
                                            data-order-id="{{ $orderMarketplace->id }}">{{ $orderMarketplace->code }}</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $orderMarketplace->created_at->format('d / m / Y') }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $orderMarketplace->client->full_name }}</span>
                            </td>
                            <td>
                                <span
                                    class="fw-bold">{{ $orderMarketplace->employee->full_name ?? 'No asignado' }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $orderMarketplace->store->name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">$ {{ $orderMarketplace->amount_total }}</span>
                            </td>
                            <td>
                                @php
                                    $hasRent = $orderMarketplace->itemOrders->contains(
                                      'sale_type',
                                      OrderSaleType::RENT->value,
                                    );
                                    $hasSale = $orderMarketplace->itemOrders->contains(
                                      'sale_type',
                                      OrderSaleType::SALE->value,
                                    );
                                @endphp

                                @if ($orderMarketplace->itemOrders->count() > 1 && $hasRent && $hasSale)
                                    Venta/Renta
                                @elseif($hasRent)
                                    Renta
                                @else
                                    Venta
                                @endif
                            </td>

                            <td class="min-w-125px">
                                <div
                                    class="badge badge-light-{{ OrderStatuses::getColor($orderMarketplace->status) }} fw-bold">
                                    {{ OrderStatuses::getName($orderMarketplace->status) }}
                                </div>
                            </td>
                            <x-actions-btn onclickDelete="eliminar({{ $orderMarketplace->id }})" />
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!--end::Table-->
        </div>

        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="col my-5">
                {{ $data->appends(request()->query())->links() }}
            </div>
        @else
            <p class="ms-10">Mostrando páginas de {{ $page_start }} a {{ $page_end }} (total registros:
                {{ count($data) }})</p>
        @endif
        <!--end::Card body-->
    </div>
    <!--end::Products-->


    <div id="barcode-tooltip" class="barcode-tooltip">
        <div class="tooltip-header">
            <strong>Artículo(s):</strong>
        </div>
        <div id="barcode-content" class="tooltip-content">
            Cargando...
        </div>
    </div>

    <x-slot name="js">
        <x-sweet-alert title="¿Estás seguro que deseas eliminar este producto del Orden de Marketplace?"
            route="{{ route('marketplace.order_marketplace.delete', '') }}"
            successTitle="El producto del Orden de Marketplace ha sido eliminado"
            successText="El producto del Orden de Marketplace ha sido eliminado exitosamente"
            errorTitle="Error al eliminar el producto del Orden de Marketplace"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const storeSelect = document.getElementById('store');
            const employeeSelect = document.getElementById('employee_id');
            const createdAtInput = document.getElementById('created_at');
            const dateStartInput = document.getElementById('date_start');
            const perPageSelect = document.getElementById('per_page');
            const saleTypeSelect = document.getElementById('sale_type');

            storeSelect.addEventListener('change', submitForm);
            employeeSelect.addEventListener('change', submitForm);
            createdAtInput.addEventListener('change', submitForm);
            dateStartInput.addEventListener('change', submitForm);
            perPageSelect.addEventListener('change', submitForm);
            if (saleTypeSelect) saleTypeSelect.addEventListener('change', submitForm);

            function submitForm() {
                filterForm.submit();
            }


            const tooltip = document.getElementById('barcode-tooltip');
            const tooltipContent = document.getElementById('barcode-content');
            const barcodeElements = document.querySelectorAll('.barcode-hover');

            barcodeElements.forEach(element => {
                element.addEventListener('mouseenter', async function(e) {
                    const orderId = this.getAttribute('data-order-id');


                    tooltip.style.display = 'block';
                    tooltipContent.innerHTML = 'Cargando...';


                    updateTooltipPosition(e);

                    try {
                        const response = await fetch(
                            `/marketplace/order-marketplace/${orderId}/barcodes`);
                        const data = await response.json();

                        if (data.success && data.items.length > 0) {
                            tooltipContent.innerHTML = data.items.map((item) =>
                                `<div class="barcode-item">${item.number}. ${item.name} - ${item.barcode}</div>`
                            ).join('');
                        } else {
                            tooltipContent.innerHTML = 'No hay artículos disponibles';
                        }
                    } catch (error) {
                        tooltipContent.innerHTML = 'Error al cargar información de artículos';
                    }
                });

                element.addEventListener('mouseleave', function() {
                    tooltip.style.display = 'none';
                });

                element.addEventListener('mousemove', updateTooltipPosition);
            });

            function updateTooltipPosition(e) {
                const offset = 15;
                tooltip.style.left = (e.pageX + offset) + 'px';
                tooltip.style.top = (e.pageY - tooltip.offsetHeight - offset) + 'px';
            }
        });
    </script>

    <style>
        .barcode-tooltip {
            position: absolute;
            background-color: #2a2a3a;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            max-width: 300px;
            border: 1px solid #3a3a4a;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .tooltip-header {
            margin-bottom: 8px;
            font-weight: bold;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .tooltip-content {
            max-height: 200px;
            overflow-y: auto;
        }

        .barcode-item {
            padding: 2px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #e0e0e0;
        }

        .barcode-hover {
            cursor: pointer;
        }
    </style>

</x-layouts.master-layout>
