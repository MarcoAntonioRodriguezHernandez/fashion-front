<x-layouts.master-layout title="Cupones" cardTitle="Cupones">
    <x-layouts.card-header :module="ModuleAliases::COUPON" :createRoute="route('base.coupon.create.view')" :createText="__('Agregar Cupón')" />
    <!--begin::Coupons-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_coupons_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Código</th>
                        <th class="min-w-125px">Usos disponibles</th>
                        <th class="min-w-125px">Descuento</th>
                        <th class="min-w-125px">Fecha de activación</th>
                        <th class="min-w-125px">Fecha de vencimiento</th>
                        <th class="min-w-125px">Estatus</th>
                        @permission(ModuleAliases::COUPON, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $coupon)
                    <tr>
                        <td>{{ $coupon['id'] }}</td>
                        <td class="min-w-125px">
                            <a href="{{ route('base.coupon.show', $coupon['id']) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">{{ $coupon['code'] }}</a>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $coupon['uses_amount'] ?? 'Ilimitado' }}</span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $coupon->formatted_discount }}</span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $coupon->date_start }}</span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $coupon->date_end }}</span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $coupon['status'] ? 'Activo' : 'Inactivo' }}</span>
                        </td>
                        @permission(ModuleAliases::COUPON, PermissionTypes::UPDATE)

                        <x-actions-btn :module="ModuleAliases::COUPON" routeEdit="{{ route('base.coupon.edit.view', $coupon['id']) }}" onclickDelete="eliminar({{ $coupon->id }})" />

                        @endpermission
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
    <!--end::Coupons-->
    <x-slot name="js">
        <x-sweet-alert title="¿Estás seguro que deseas eliminar este Cupón?" route="{{ route('base.coupon.delete', '') }}" successTitle="El cupón ha sido eliminado" successText="El cupón ha sido eliminado exitosamente" errorTitle="Error al eliminar el cupón" errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
