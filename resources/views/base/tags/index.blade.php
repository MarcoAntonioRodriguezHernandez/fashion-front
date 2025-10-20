<x-layouts.master-layout title="Etiquetas" cardTitle="Etiquetas">

    <x-layouts.card-header :createRoute="route('base.tags.create.view')" :createText="__('Agregar Etiqueta')" :module="ModuleAliases::TAG" />

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
                        <th class="min-w-125px">Código etiqueta</th>
                        @permission(ModuleAliases::TAG, PermissionTypes::UPDATE)
                            <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $tag)
                    <tr>
                        <td>{{ $tag['id'] }}</td>
                        <td class="min-w-125px">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <!--begin::Title-->
                                    <a href="{{ route('base.tags.show', $tag['id']) }}"
                                        class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                        data-kt-ecommerce-product-filter="product_name">{{ $tag['name'] }}</a>
                                    <!--end::Title-->
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $tag->slug}}</span>
                        </td>
                        <x-actions-btn routeEdit="{{ route('base.tags.edit.view', $tag['id']) }}"
                            onclickDelete="eliminar({{ $tag->id }})" :module="ModuleAliases::TAG"  />
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar esta etiqueta?" route="{{ route('base.tags.delete', '') }}"
            successTitle="La etiqueta ha sido eliminado" successText="La etiqueta ha sido eliminado exitosamente"
            errorTitle="Error al eliminar la etiqueta" errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
