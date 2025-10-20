<x-layouts.master-layout title="Mostrar rol" :cardTitle="$data->name">
    <div class="row">
        <!--begin::Sidebar-->
        <div class="col-12 col-lg-5 col-xl-4 mb-10">
            <!--begin::Card-->
            <div class="card card-flush shadow">
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Description-->
                    <div class="fs-6 fw-bold text-gray-700 mb-5">{{ $data->description }}</div>
                    <div class="fw-bold text-gray-600 mb-5">{{ $data->permissions()->count() }} permisos(s) para este rol
                    </div>
                    <!--end::Description-->
                    <!--begin::Permissions-->
                    <div class="col text-gray-600 mh-lg-500px overflow-auto">
                        @foreach ($data->permissions as $permission)
                            <div class="d-flex justify-content-between py-2 overflow-visible">
                                <div>
                                    <span class="bullet bg-primary me-3"></span>{{ $permission->module->name }}
                                </div>
                                <div>
                                    @if ($permission->read || $permission->update || $permission->create)
                                        @if ($permission->read)
                                            <span class="badge badge-roleReading fs-7 m-1" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                                                title="Lectura">
                                                <i class="ki-duotone ki-eye text-dark ">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </span>
                                        @endif
                                        @if ($permission->update)
                                            <span class="badge badge-roleWriting fs-7 m-1" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                                                title="Escritura">
                                                <i class="ki-duotone ki-pencil text-dark ">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        @endif
                                        @if ($permission->create)
                                            <span class="badge badge-roleCreation fs-7 m-1" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                                                title="Creación">
                                                <i class="ki-duotone ki-plus-circle text-dark ">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        @endif
                                    @else
                                        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                            data-bs-placement="top" title="Ninguno"
                                            class="badge badge-roleNone text-dark fs-7 m-1">
                                            <i class="ki-duotone ki-cross-circle text-dark">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!--end::Permissions-->
                </div>
                <!--end::Card body-->
                <!--begin::Card footer-->
                <div class="card-footer pt-0">
                    <div class="d-flex">
                        <a href="{{ route('auth.roles.edit.view', $data->id) }}"
                            class="btn btn-light btn-active-primary">Editar Rol</a>
                    </div>
                </div>
                <!--end::Card footer-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Sidebar-->
        <!--begin::Content-->
        <div class="col ms-lg-10">
            <!--begin::Card-->
            <div class="card card-flush mb-6 mb-xl-9 shadow">
                <!--begin::Card header-->
                <div class="card-header pt-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="d-flex align-items-center">Usuarios Asignados
                            <span class="text-gray-600 fs-6 ms-3">({{ $data->users()->count() }})</span>
                        </h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0 table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_roles_view_table">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">ID</th>
                                <th class="min-w-150px">Usuario</th>
                                <th class="min-w-125px">Fecha de Ingreso</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach ($data->users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td class="d-flex align-items-center">
                                        <!--begin:: Avatar -->
                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                            <div class="symbol-label">
                                                <img src="{{ asset($user->photo ?: 'src/img/user-image.png') }}"
                                                    alt="{{ $user->username }} nombre" class="w-100" />
                                            </div>
                                        </div>
                                        <!--end::Avatar-->
                                        <!--begin::User details-->
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('base.user.show', $user->id) }}"
                                                class="text-gray-800 text-hover-primary mb-1">{{ $user->full_name }}</a>
                                            <span>{{ $user->email }}</span>
                                        </div>
                                        <!--begin::User details-->
                                    </td>
                                    <td>{{ strftime('%d / %b / %Y, %R', strtotime($user->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->

            </div>
            <!--end::Card-->
        </div>
        <!--end::Content-->
    </div>
</x-layouts.master-layout>
