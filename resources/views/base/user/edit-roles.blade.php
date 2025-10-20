<x-layouts.master-layout title="Editar Perfil" cardTitle="Actualizar roles de {{ $data->user->name }} {{ $data->user->last_name }} ">
    <div class="form_padding">
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <!--end:::Tabs-->
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <!--begin::Inventory-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card body-->
                        <div class="card-body pt-0 mt-4">
                            <form id="staffAdd" method="POST" action="{{ route('base.user.update_roles', $data->user->id) }}">
                                @csrf
                                @method('PUT')

                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-8">
                                    <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-125px text-center">Asignar</th>
                                            <th class="min-w-125px">Nombre</th>
                                            <th class="min-w-125px">Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @foreach ($data->roles as $role)
                                            <tr>
                                                <td>
                                                    <div class="col-12 form-check form-check-sm form-check-custom form-check-solid">
                                                        <input name="roles[{{ $role->id }}]" class="form-check-input mx-auto" type="checkbox" value="{{ $role->id }}" @checked(old('roles.' . $role->id, $data->currentRoles->contains($role->id))) />
                                                    </div>
                                                </td>
                                                <td class="min-w-125px">
                                                    <div class="d-flex align-items-center">
                                                        <div class="">
                                                            <!--begin::Title-->
                                                            <a href="{{ route('auth.roles.show', $role->id) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $role->name }}</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">{{ $role->description }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <x-btn-cancel-save routeCancel="{{ route('base.user.index') }}" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            let validations = [
                'password',
                'email',
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
        </script>
    </x-slot>
</x-layouts.master-layout>
