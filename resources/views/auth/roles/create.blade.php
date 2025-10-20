<x-layouts.master-layout title="Registro de rol" cardTitle="Registro de rol">
    <form class="form" id="createRole" action="{{ route('auth.roles.create') }}" method="POST">
        @csrf

        <div class="card col-12 mb-xl-8 flex-grow-1">
            <div class="card-body border-top p-9">
                <div class="col">
                    <label class="fs-5 fw-bold form-label mb-2">
                        <span>Datos del Rol</span>
                    </label>
                    <div class="col mx-5">
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nombre</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="fv-row col-lg-8">
                                <x-layouts.inputs typeInput="justInput" typeofInput="text" name="name" id="name" placeholder="Nombre del Rol" />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Descripción</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="fv-row col-lg-8">
                                <x-layouts.inputs typeInput="justInput" typeofInput="text" name="description" id="description" placeholder="Descripción" />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>

                <hr class="my-15">

                <div class="col">
                    <label class="fs-5 fw-bold form-label mb-2">
                        <span>Permisos del Rol</span>
                    </label>

                    <!--begin::Table wrapper-->
                    <div class="table-responsive mx-5 mh-500px">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-semibold">
                                <!--begin::Table row-->
                                <tr>
                                    <td class="text-gray-800">Acceso de Administrador
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Permitir acceso completo al sistema">
                                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </span>
                                    </td>
                                    <td>
                                        <!--begin::Wrapper-->
                                        <div class="d-flex justify-content-evenly">
                                            <!--begin::Checkbox-->
                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                <input class="form-check-input" type="checkbox" value="" onchange="toggleAll()" id="toggle-all-check" />
                                                <span class="form-check-label" for="kt_roles_select_all">Seleccinar
                                                    Todos</span>
                                            </label>
                                            <!--end::Checkbox-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </td>
                                </tr>
                                <!--end::Table row-->
                                @foreach ($data['modules'] as $module)
                                    <!--begin::Table row-->
                                    <tr>
                                        <!--begin::Label-->
                                        <td class="text-gray-800" onclick="toggleAll('{{ $module->id }}')">
                                            {{ $module->name }}
                                        </td>
                                        <!--end::Label-->
                                        <!--begin::Input group-->
                                        <td>
                                            <!--begin::Wrapper-->
                                            <div class="d-flex justify-content-evenly">
                                                @foreach (PermissionTypes::getAllNames() as $value => $name)
                                                    <!--begin::Checkbox-->
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                        <input class="form-check-input" type="checkbox" value="1" name="permissions[{{ $module->id }}][{{ $value }}]"
                                                            @checked(old('permissions.' . $module->id . '.' . $value)) onchange="updateAllChecked()" />
                                                        <span class="form-check-label">{{ $name }}</span>
                                                    </label>
                                                    <!--end::Checkbox-->
                                                @endforeach
                                            </div>
                                            <!--end::Wrapper-->
                                        </td>
                                        <!--end::Input group-->
                                    </tr>
                                    <!--end::Table row-->
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table wrapper-->
                </div>
            </div>
        </div>

        <x-btn-create-cancel 
        routeCancel="{{ route('auth.roles.index') }}"
        :module="ModuleAliases::ROLE"
        />
    </form>

    <x-slot name="js">
        @vite('resources/js/src/roles/permissions.js')

        <script type="module">
            updateAllChecked();
        </script>
    </x-slot>
</x-layouts.master-layout>
