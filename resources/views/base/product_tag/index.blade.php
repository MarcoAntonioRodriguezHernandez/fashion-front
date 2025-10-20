<x-layouts.master-layout title="Tags de productos" cardTitle="Tags de productos">

    <x-layouts.card-header :createRoute="route('base.product_tag.create.view')" :createText="__('Agregar tags de productos')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Producto</th>
                        <th class="min-w-125px">Tag</th>
                        <th class="min-w-125px text-start">Acciones</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $productTag)
                        <tr>
                            <td><a href="{{ route('base.product_tag.show', $productTag['id']) }}" class="symbol symbol-50px">
                                {{ $productTag['id'] }}
                            </a></td>
                            <td>
                                <span class="fw-bold">{{ $productTag->product->full_name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $productTag->tag->name }}</span>
                            </td>
                            <x-actions-btn routeEdit="{{ route('base.product_tag.edit.view', $productTag['id']) }}" onclickDelete="eliminar({{ $productTag->id }})" />
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
        title="¿Estas seguro que deseas eliminar este tag de producto?"
        route="{{ route('base.product_tag.delete', '') }}"
        successTitle="El tag de producto ha sido eliminado"
        successText="El tag de producto ha sido eliminado exitosamente"
        errorTitle="Error al eliminar el tag de producto"
        errorText="Error al eliminar, intenta nuevamente"
        />
    </x-slot>

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>
