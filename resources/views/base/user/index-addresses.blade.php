<x-layouts.master-layout title="Direcciones del Usuario" cardTitle="Direcciones del Usuario {{ $data->full_name }}">
    <x-layouts.card-header :createRoute="route('base.user_addresses.create.view', $data->id)" :createText="__('Agregar Dirección')" />

    <div class="card card-flush py-4">
        <!--begin::Card header-->
        <div class="card-header">
            <!--begin::Card body-->
            <div class="card-body pt-0 mt-4">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="max-w-125px">Calle</th>
                            <th class="max-w-125px">Número Interior</th>
                            <th class="max-w-125px">Número Exterior</th>
                            <th class="max-w-125px">Colonia</th>
                            <th class="max-w-125px">Ciudad</th>
                            <th class="max-w-125px">Estado</th>
                            <th class="max-w-125px">Código Postal</th>
                            <th class="max-w-125px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($data->userAddresses as $address)
                            <tr>
                                <td class="min-w-125px">
                                    <a href="{{ route('base.user_addresses.show', $address->id) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $address->street }}</a>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $address->interior_number ?? 'S/N' }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $address->external_number ?? 'S/N' }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $address->colony }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $address->city }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $address->state }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $address->zip_code }}</span>
                                </td>
                                <x-actions-btn routeEdit="{{ route('base.user_addresses.edit.view', $address->id) }}" onclickDelete="eliminar({{ $address->id }})" />
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!--end::Table-->

                <!--begin::Actions-->
                <div class="text-end mt-7">
                    <a href="{{ route('base.user.show', $data->id) }}" class="btn btn-primary mx-4">
                        Regresar
                    </a>
                </div>
                <!--end::Actions-->
            </div>
        </div>
    </div>

    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>
        <x-sweet-alert
            title="¿Estas seguro que deseas eliminar esta dirección?"
            route="{{ route('base.user_addresses.delete', '') }}"
            successTitle="La dirección ha sido eliminada"
            successText="La dirección ha sido eliminada exitosamente"
            errorTitle="Error al eliminar la dirección"
            errorText="Error al eliminar, intenta nuevamente" />

        <script>
            let validations = [
                'new_password',
                'confirm_password',
            ].reduce((acc, field) => (acc[field] = {
                validators: {
                    notEmpty: {
                        message: '{{ __('validation.required', ['attribute' => ':attr']) }}'.replace(':attr',
                            field)
                    }
                }
            }, acc), {});

            window.addEventListener('load', () => {
                GeneralForm.init('staffAdd', validations,
                    'Error en la validación de los campos, por favor verifique los datos e intente de nuevo.')
            });

            var passwordMeter = new KTPasswordMeter(document.getElementById("passwordMeterElement"), {
                /* options */
            });
        </script>
    </x-slot>
</x-layouts.master-layout>
