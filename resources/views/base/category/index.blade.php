<x-layouts.master-layout title="Categorías" cardTitle="Categorías">

    <x-layouts.card-header :module="ModuleAliases::CATEGORY" :createRoute="route('base.category.create.view')" :createText="__('Agregar categoría')" />

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
                        <th class="min-w-125px">Código categoría</th>
                        <th class="min-w-125px">Categoría padre</th>
                        <th class="min-w-125px">Estado</th>
                        @permission(ModuleAliases::CATEGORY, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $category)
                    <tr>
                        <td>{{ $category['id'] }}</td>
                        <td class="min-w-125px">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <!--begin::Title-->
                                    <a href="{{ route('base.category.show', $category['id']) }}"
                                        class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                        data-kt-ecommerce-product-filter="product_name">{{ $category['name'] }}</a>
                                    <!--end::Title-->
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $category->slug}}</span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $category['parentCategory']['name'] ?? 'No definido' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-light-{{CategoryStatuses::getColor($category->status) }} fw-bold">
                                {{ CategoryStatuses::getName($category->status) }}
                            </span>
                        </td>
                        <x-actions-btn routeEdit="{{ route('base.category.edit.view', $category['id']) }}"
                            onclickDelete="eliminar({{ $category->id }})" :module="ModuleAliases::CATEGORY" />
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar esta categoría?"
            route="{{ route('base.category.delete', '') }}" successTitle="La categoría ha sido eliminado"
            successText="La categoría ha sido eliminado exitosamente" errorTitle="Error al eliminar la categoría"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
