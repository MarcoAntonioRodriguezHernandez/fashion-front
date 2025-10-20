<x-layouts.master-layout title="Imagenes de Productos" cardTitle="Imagenes de Productos">

    <x-layouts.card-header :createRoute="route('base.product_image.create.view')" :createText="__('Agregar Imagen al producto')" />

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Imagen</th>
                        <th class="min-w-125px">Producto</th>
                        <th class="min-w-150px">Color</th>
                        <th class="min-w-125px">Orden</th>
                        <th class="min-w-125px">Perspectiva de Fotografia</th>
                        <th class="min-w-125px text-start">Acciones</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $productImage)
                    <tr>
                        <td>{{$productImage->id}}</td>
                        <td class="min-w-125px">
                            <div class="d-flex align-items-center">
                                <!--begin::Thumbnail-->
                                <a href="{{ route('base.product_image.show', $productImage['id']) }}" class="symbol symbol-50px">
                                    <span class="symbol-label" style="background-image: url('{{ asset($productImage['src_image']) }}');"></span>
                                </a>
                                <!--end::Thumbnail-->
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $productImage->product->full_name }}</span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $productImage->color->name }}</span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $productImage['order']  }}</span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $productImage['camera_perspective']  }}</span>
                        </td>
                        <x-actions-btn routeEdit="{{ route('base.product_image.edit.view', $productImage['id']) }}"
                            onclickDelete="eliminar({{ $productImage->id }})" />
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar esta imagen?"
            route="{{ route('base.product_image.delete', '') }}" successTitle="La imagen del producto ha sido eliminado"
            successText="El ejemplo ha sido eliminado exitosamente" errorTitle="Error al eliminar la  imagen del producto"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>