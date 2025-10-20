<x-layouts.master-layout title="Marketplaces" cardTitle="Marketplaces">

    <x-layouts.card-header :module="ModuleAliases::MARKETPLACE" :createText="__('Agregar marketplace')" />

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
                        <th class="min-w-125px">URL</th>
                        @permission(ModuleAliases::MARKETPLACE, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $marketplace)
                        <tr>
                            <td>{{ $marketplace['id'] }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="ms-5">
                                        <!--begin::Title-->
                                        <a href="{{ route('base.marketplace.show', $marketplace['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">{{ $marketplace['name'] }}</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $marketplace['slug'] }}</span>
                            </td>
                            <td>
                                <a href="{{ $marketplace['url'] }}" target="_blank" class="fw-bold">{{ $marketplace['url'] }}</a>
                            </td>
                            <x-actions-btn :module="ModuleAliases::MARKETPLACE" routeEdit="{{ route('base.marketplace.edit.view', $marketplace['id']) }}" />
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

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
