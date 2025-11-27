<x-layouts.master-layout title="Distribuciones" cardTitle="Distribuciones">

    <x-layouts.card-header :module="ModuleAliases::SUPPLY" :createRoute="route('base.supply.create.view')" :createText="__('Agregar Suministros')" :module="ModuleAliases::SUPPLY" :searchBy="$searchBy" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="text-center min-w-125x">Código</th>
                        <th class="text-center min-w-125px">Remitente</th>
                        <th class="text-center min-w-115px">Fecha de Creación</th>
                        <th class="text-center min-w-80px">Estatus</th>
                        <th class="text-center min-w-125px">Automática</th>
                        @permission(ModuleAliases::SUPPLY, PermissionTypes::UPDATE)
                            <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $supply)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $supply->id }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('base.supply.show', $supply->id) }}"
                                    class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    data-kt-ecommerce-product-filter="product_id">{{ $supply->code }}</a>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold">{{ $supply->sender->full_name }}</span>
                            </td>
                            <td class="text-center min-w-auto">
                                <span class="fw-bold">{{ $supply['shipping_date'] }}</span>
                            </td>
                            <td class="text-center">
                                <div class="badge badge-light-{{ SupplyStatuses::getColor($supply->status) }} fw-bold">
                                    {{ SupplyStatuses::getName($supply->status) }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold">{{ $supply->is_automatic ? 'Sí' : 'No' }}</span>
                            </td>
                            <x-actions-btn :module="ModuleAliases::SUPPLY"
                                routeEdit="{{ route('base.supply.edit.view', $supply->id) }}"
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar esta distribución?"
            route="{{ route('base.supply.delete', '') }}" successTitle="La distribución ha sido eliminada"
            successText="La distribución ha sido eliminada exitosamente" errorTitle="Error al eliminar la distribución"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
