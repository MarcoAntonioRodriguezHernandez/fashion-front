<x-layouts.master-layout title="Productos" cardTitle="Productos">
         <form method="GET" class="d-flex justify-content-between align-items-center mb-5">
                <div class="ms-10">
                    <label for="per_page" class="form-label fw-bold mb-0">Mostrar:</label>
                    <select name="per_page" id="per_page" class="form-select" style="width: 100px;" onchange="this.form.submit()">
                        @foreach ([10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

    <x-layouts.card-header :createRoute="route('base.product.full_create.view')" :createText="__('Agregar producto')" :module="ModuleAliases::PRODUCT" :searchBy="$searchBy" />

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
                        <th class="min-w-125px">Título</th>
                        <th class="min-w-150px">Precio Completo</th>
                        <th class="min-w-125px">Categoría</th>
                        <th class="min-w-125px">Marca / Diseñador</th>
                        <th class="min-w-125px">Artículos</th>
                        @permission(ModuleAliases::PRODUCT, PermissionTypes::UPDATE)
                            <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $product)
                        <tr>
                            <td>{{ $product['id'] }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <!--begin::Name-->
                                        <a href="{{ route('base.product.show', $product['id']) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">{{ $product['name'] }}</a>
                                        <!--end::Name-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $product['title'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">$ {{ $product['full_price'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $product['category']->name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $product['designer']->name }}</span>
                            </td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <!--begin::Title-->
                                        <a href="{{ route('base.supply.create.view', $product['id']) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ trans_choice(':value artículo registrado|:value artículos registrados', $product->items()->count(), ['value' => $product->items()->count()]) }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <x-actions-btn routeEdit="{{ route('base.product.edit.view', $product['id']) }}"
                                onclickDelete="eliminar({{ $product->id }})" :module="ModuleAliases::PRODUCT">
                                <a class="menu-link px-2 pb-0" href="{{ route('base.product.variants.view', $product->id) }}" style="cursor: pointer;">
                                    <i class="ki-duotone ki-arrow-right-left fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </a>
                            </x-actions-btn>
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
    <!--end::Products-->

    <x-slot name="js">
        <x-sweet-alert title="¿Estas seguro que deseas eliminar este producto?" route="{{ route('base.product.delete', '') }}" successTitle="El producto ha sido eliminado" successText="El producto ha sido eliminado exitosamente" errorTitle="Error al eliminar el producto" errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
