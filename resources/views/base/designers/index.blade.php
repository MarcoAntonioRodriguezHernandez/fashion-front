<x-layouts.master-layout title="Diseñadores" cardTitle="Diseñadores">

    <x-layouts.card-header :module="ModuleAliases::DESIGNER" :createRoute="route('base.designers.create.view')" :createText="__('Agregar diseñador')" />

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
                        <th class="min-w-125px">Slug</th>
                        @permission(ModuleAliases::DESIGNER, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $designer)
                        <tr>
                            <td>{{ $designer['id'] }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="ms-5">
                                        <!--begin::Title-->
                                        <a href="{{ route('base.designers.show', $designer['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">{{ $designer['name'] }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $designer['slug'] }}</span>
                            </td>
                            <x-actions-btn :module="ModuleAliases::DESIGNER" routeEdit="{{ route('base.designers.edit.view', $designer['id']) }}" onclickDelete="eliminar({{ $designer->id }})" />
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
        title="¿Estas seguro que deseas eliminar este diseñador?"
        route="{{ route('base.designers.delete', '') }}"
        successTitle="El diseñador ha sido eliminado"
        successText="El diseñador ha sido eliminado exitosamente"
        errorTitle="Error al eliminar el diseñador"
        errorText="Error al eliminar, intenta nuevamente"
        />
    </x-slot>

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>
