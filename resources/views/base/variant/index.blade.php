<x-layouts.master-layout title="Variantes" cardTitle="Variantes">

    <x-layouts.card-header :module="ModuleAliases::VARIANT" :createRoute="route('base.variant.create.view')" :createText="__('Agregar variante')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-150px">Código</th>
                        <th class="min-w-125px">Talla</th>
                        <th class="min-w-125px">Color</th>
                        <th class="min-w-125px">Estado</th>
                        @permission(ModuleAliases::VARIANT, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $variant)
                        <tr>
                            <td>
                                <span>{{ $variant['id'] }}</span>
                            </td>
                            <td>
                                <a href="{{ route('base.variant.show', $variant['id']) }}">
                                    {{ $variant['code'] }}
                                </a>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $variant->size->full_name }}</span>
                            </td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <!--begin::Thumbnail-->
                                    <a class="symbol symbol-30px">
                                        <span class="symbol-label" style="background-color: {{ $variant->color->hexadecimal }};"></span>
                                    </a>
                                    <!--end::Thumbnail-->
                                    <div class="ms-5">
                                        <!--begin::Title-->
                                        <a
                                            class="text-gray-800 text-hover-primary fs-5"
                                            data-kt-ecommerce-product-filter="product_name">{{ $variant->color->name }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td class="min-w-125px">
                                @if ($variant['status'] == 1)
                                    <div class="badge badge-light-success fw-bold">{{ __('Activo') }}
                                    </div>
                                @else
                                    <div class="badge badge-light-danger fw-bold">{{ __('Inactivo') }}
                                    </div>
                                @endif
                            </td>
                            <x-actions-btn routeEdit="{{ route('base.variant.edit.view', $variant['id']) }}"
                                onclickDelete="eliminar({{ $variant->id }})" :module="ModuleAliases::VARIANT"/>
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar esta variante?"
            route="{{ route('base.variant.delete', '') }}" successTitle="La variante ha sido eliminado"
            successText="La variante ha sido eliminado exitosamente" errorTitle="Error al eliminar la variante"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
