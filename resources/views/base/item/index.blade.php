<x-layouts.master-layout title="Artículos" cardTitle="Artículos">

    <form method="GET" action="{{ route('base.item.index') }}" class="d-flex align-items-end gap-3" id="filterForm">
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
            <label for="per_page" class="form-label fw-bold mb-0">Mostrar:</label>
            <select name="per_page" id="per_page" class="form-select" style="width: 100px;"
                onchange="this.form.submit()">
                @foreach ([10, 25, 50] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    <x-layouts.card-header :createRoute="route('base.stock.add.view')" :createText="'Agregar Inventario'" :module="ModuleAliases::ITEM" :searchBy="$searchBy" />

    <!--begin::Items-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="max-w-125px">Código CM</th>
                        <th class="max-w-125px">Nombre del producto</th>
                        <th class="min-w-125px">Nombre del almacenaje</th>
                        <th class="min-w-125px">Variante</th>
                        <th class="min-w-150px">Precio de venta</th>
                        <th class="min-w-125px">Integridad</th>
                        <th class="min-w-125px">Condición</th>
                        <th class="min-w-125px">Estatus</th>
                        @permission(ModuleAliases::ITEM, PermissionTypes::UPDATE)
                            <th class="min-w-125px">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $item)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $item->id }}</span>
                            </td>
                            <td><a href="{{ route('base.item.show', $item->id) }}">
                                    <span class="fw-bold">{{ $item->barcode ?? 'Sin asignar' }}</span>
                                </a>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $item->product->full_name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $item->store->name }}</span>
                            </td>
                            <td>
                                @php 
                                    $bgColor = $item->variant->color->hexadecimal ?? '#ccc'; 
                                    $hex = str_replace('#', '', $bgColor);
                                    $r = hexdec(substr($hex, 0, 2));
                                    $g = hexdec(substr($hex, 2, 2));
                                    $b = hexdec(substr($hex, 4, 2));
                                    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
                                    $textColor = $luminance > 0.5 ? '#000000' : '#ffffff';
                                @endphp   
                                <span class="fw-bold badge p-3 border border-dark"
                                    style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                    {{ $item->variant->size->full_name }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold">$ {{ $item->price_sale }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $item->integrity_name }}</span>
                            </td>
                            <td>
                                <div class="badge badge-{{ $item->condition_color }} fw-bold">
                                    {{ $item->condition_name }}</div>
                            </td>
                            <td class="min-w-125px">
                                <span class="badge badge-light-{{ ItemStatuses::getColor($item->status) }} fw-bold">
                                    {{ ItemStatuses::getName($item->status) }}
                                </span>
                            </td>
                            <x-actions-btn routeEdit="{{ route('base.item.edit.view', $item->id) }}"
                                onclickDelete="eliminar({{ $item->id }})" :module="ModuleAliases::ITEM" />
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <div class="col my-5">
            {{ $data->appends(request()->query())->links() }}
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Items-->

    <x-slot name="js">
        <x-sweet-alert title="¿Estas seguro que deseas eliminar este artículo?"
            route="{{ route('base.item.delete', '') }}" successTitle="El artículo ha sido eliminado"
            successText="El artículo ha sido eliminado exitosamente" errorTitle="Error al eliminar el artículo"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection

    <script>
        document.getElementById('store').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
</x-layouts.master-layout>
