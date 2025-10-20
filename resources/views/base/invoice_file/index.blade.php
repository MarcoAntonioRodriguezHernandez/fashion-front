<x-layouts.master-layout title="Ejemplos" cardTitle="Ejemplos">

    <x-layouts.card-header :createRoute="route('example.create.view')" :createText="__('Agregar ejemplos')" />

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
                        <th class="min-w-125px">Tipo de ejemplo</th>
                        <th class="min-w-150px">Descripción:</th>
                        <th class="min-w-125px">Valor</th>
                        <th class="min-w-125px">Cantidad</th>
                        <th class="min-w-125px">Estado</th>
                        <th class="min-w-125px text-start">Acciones</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $example)
                        <tr>
                            <td>{{ $example['id'] }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <!--begin::Thumbnail-->
                                    <a href="{{ route('example.show', $example['id']) }}" class="symbol symbol-50px">
                                        <span class="symbol-label" style="background-image: url('{{ asset($example['image']) }}');"></span>
                                    </a>
                                    <!--end::Thumbnail-->
                                    <div class="ms-5">
                                        <!--begin::Title-->
                                        <a href="{{ route('example.show', $example['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">{{ $example['name'] }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $example->exampleType->name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $example['description'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $example['value'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $example['quantity'] }}</span>
                            </td>
                            <td class="min-w-125px">
                                @if ($example['status'] == 1)
                                    <div class="badge badge-light-success fw-bold">{{ __('Activo') }}
                                    </div>
                                @else
                                    <div class="badge badge-light-danger fw-bold">{{ __('Inactivo') }}
                                    </div>
                                @endif
                            </td>
                            <x-actions-btn routeEdit="{{ route('example.edit.view', $example['id']) }}" onclickDelete="eliminar({{ $example->id }})" />
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
        title="¿Estas seguro que deseas eliminar este ejemplo?"
        route="{{ route('example.delete', '') }}"
        successTitle="El ejemplo ha sido eliminado"
        successText="El ejemplo ha sido eliminado exitosamente"
        errorTitle="Error al eliminar el ejemplo"
        errorText="Error al eliminar, intenta nuevamente"
        />
    </x-slot>

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>