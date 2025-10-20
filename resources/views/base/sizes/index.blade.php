<x-layouts.master-layout title="Tallas" cardTitle="Tallas">

    <x-layouts.card-header :module="ModuleAliases::SIZE" :createRoute="route('base.sizes.create.view')" :createText="__('Agregar talla')" />

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
                        <th class="min-w-125px">Color</th>
                        <th class="min-w-125px">Status</th>
                        @permission(ModuleAliases::SIZE, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $size)
                        <tr>
                            <td>{{ $size->id }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="ms-5">
                                        <!--begin::Title-->
                                        <a href="{{ route('base.sizes.show', $size->id) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">{{ $size->full_name }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <!--begin::Thumbnail-->
                                    <a class="symbol symbol-30px">
                                        <span class="symbol-label" style="background-color: {{ $size->hex_color }};"></span>
                                    </a>
                                    <!--end::Thumbnail-->
                                </div>
                            </td>
                            <td class="min-w-125px">
                                @if ($size->status)
                                    <div class="badge badge-light-success fw-bold">Visible</div>
                                @else
                                    <div class="badge badge-light-danger fw-bold">No Visible</div>
                                @endif
                            </td>
                            <x-actions-btn :module="ModuleAliases::SIZE" routeEdit="{{ route('base.sizes.edit.view', $size->id) }}" onclickDelete="eliminar({{ $size->id }})" />
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
        title="¿Estas seguro que deseas eliminar esta talla?"
        route="{{ route('base.sizes.delete', '') }}"
        successTitle="El talla ha sido eliminada"
        successText="El talla ha sido eliminada exitosamente"
        errorTitle="Error al eliminar el talla"
        errorText="Error al eliminar, intenta nuevamente"
        />
    </x-slot>

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>
