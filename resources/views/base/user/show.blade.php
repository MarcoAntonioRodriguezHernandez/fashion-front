<x-layouts.master-layout title="Perfil de usuario" cardTitle="Perfil de usuario">
    <!--begin::Navbar-->
    <!--end::Navbar-->
    <div class="form_padding">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-4">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <!--begin: Pic-->
                    <div class="me-7 mb-4">
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                            data-kt-image-input="true">
                            <!--begin::Preview existing avatar-->
                            <div class="image-input-wrapper w-250px h-250px"
                                style="background-image: url('{{ asset($data->photo ?: 'src/img/user-image.png') }}')">
                            </div>
                            <!--end::Remove-->
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Actions-->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-end">
                                <span class="text-gray-900 fs-1 fw-bold me-1">{{ $data->full_name }}</span>
                                @if ($data->email_verified_at != null)
                                    <span>
                                        <i class="ki-outline ki-verify fs-1 text-success"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="text-start">
                                @permission(ModuleAliases::USER, PermissionTypes::UPDATE)
                                    @if (Auth::id() == $data->id)
                                        <a href="{{ route('base.user.edit.view', $data->id) }}"
                                            class="btn btn-primary me-3">Editar perfil</a>
                                    @endif
                                @endpermission
                                <div class="dropdown d-inline">
                                    <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                                    </button>
                                    <!--begin::Menu 3-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                        data-kt-menu="true">
                                        <!--begin::Heading-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                Operaciones
                                            </div>
                                        </div>
                                        <!--end::Heading-->
                                        @permission(ModuleAliases::USER, PermissionTypes::UPDATE)
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('base.user.edit_roles', $data->id) }}"
                                                    class="menu-link flex-stack px-3">Actualizar Roles</a>
                                            </div>
                                            <!--end::Menu item-->
                                        @endpermission
                                        @if (Auth::id() == $data->id)
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('base.user.edit_email', $data->id) }}"
                                                    class="menu-link px-3">Actualizar Correo</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('base.user.edit_password', $data->id) }}"
                                                    class="menu-link flex-stack px-3">Actualizar Contraseña</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('base.user.addresses', $data->id) }}"
                                                    class="menu-link flex-stack px-3">Lista de Direcciones</a>
                                            </div>
                                            <!--end::Menu item-->
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Actions-->
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Info-->
                                <div class="d-flex flex-column fw-semibold fs-6 mb-4 mt-4 pe-2">
                                    <span class="d-flex align-items-center text-gray-500 me-5 mb-4">
                                        <i class="ki-outline ki-profile-circle fs-2 me-1"></i>
                                        Staff
                                    </span>
                                    <span class="d-flex align-items-center text-gray-500 mb-4">
                                        <i class="ki-outline ki-sms fs-2 me-1"></i>
                                        {{ $data->email }}
                                    </span>
                                    {{-- if theres any roles dont show --}}
                                    @if ($data->roles->count() > 0)
                                        <span class="card-label fw-bold fs-4 mb-1 mx-2">Roles:</span>
                                        <div class="d-flex align-items-center flex-wrap text-gray-500 mb-4">
                                            @foreach ($data->roles as $role)
                                                <span class="badge badge-light-primary fs-7 mx-2 mt-2">
                                                    <i class="ki-duotone ki-security-user text-dark fs-2"
                                                        style="margin-right: 2px;">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Title-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
            </div>
        </div>
        <!--begin::Form-->
        <div class="form d-flex flex-column flex-lg-row">
            <!--end::Aside column-->
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--end:::Tabs-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Inventory-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <label class="form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name"
                                        placeholder="Nombre" value="{{ $data->name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Apellido</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name"
                                        placeholder="Nombre" value="{{ $data->last_name }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Correo</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="name" id="name"
                                        placeholder="Nombre" value="{{ $data->email }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Teléfono</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" name="phone" id="phone"
                                        placeholder="Teléfono" value="{{ $data->phone }}" disabled />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Tab content-->

                    @if ($data->employeeDetail)
                        <!--begin::Inventory-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card body-->
                                <div class="card-body">
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="form-label">Tienda</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputdisabled"
                                            value="{{ $data->employeeDetail->store->name }}" disabled />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="form-label">Recibe Cancelaciones</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputdisabled"
                                            value="{{ $data->employeeDetail->notify_cancellations ? 'Sí' : 'No' }}"
                                            disabled />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Card header-->
                            </div>
                            <!--end::Inventory-->
                        </div>
                        <!--end::Tab content-->
                    @endif

                    @if ($data->clientDetail)
                        <div class="row row-cols-1 row-cols-xl-2">
                            <div class="col px-4 mb-10">
                                <!--begin::Inventory-->
                                <div class="card card-flush py-4 h-100">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <!--begin::Card body-->
                                        <div class="card-body">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label">Fecha de Nacimiento</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <x-layouts.inputs typeInput="justInputdisabled"
                                                    value="{{ $data->clientDetail->date_of_birth ?? 'No Especificado' }}"
                                                    disabled />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label">Género</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <x-layouts.inputs typeInput="justInputdisabled"
                                                    value="{{ $data->clientDetail->gender_name }}" disabled />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Inventory-->
                                </div>
                                <!--end::Tab content-->
                            </div>

                            <div class="col px-4 mb-10">
                                <!--begin::Inventory-->
                                <div class="card card-flush py-4 h-100">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <!--begin::Card body-->
                                        <div class="card-body">
                                            <!--begin::Title-->
                                            <h3 class="card-title text-gray-800 mb-9">Crédito pendiente</h3>
                                            <!--end::Title-->

                                            <div class="table-responsive">
                                                <!--begin::Table-->
                                                <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                                    <!--begin::Table head-->
                                                    <thead>
                                                        <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                                            <th class="p-0 pb-3 text-start">Cantidad</th>
                                                            <th class="p-0 pb-3 text-end">Vencimiento</th>
                                                        </tr>
                                                    </thead>
                                                    <!--end::Table head-->

                                                    <!--begin::Table body-->
                                                    <tbody>
                                                        @forelse ($data->clientDetail->clientCredits as $clientCredit)
                                                            <tr>
                                                                <td>$ {{ $clientCredit->amount }}</td>
                                                                <td class="text-end">
                                                                    {{ $clientCredit->expiration_date }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="text-center" colspan="100%">Sin créditos
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                    <!--end::Table body-->
                                                </table>
                                                <!--end::Table-->
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Inventory-->
                                </div>
                                <!--end::Tab content-->
                            </div>
                        </div>
                    @endif

                    <x-btn-cancel-save :module="ModuleAliases::USER"
                        routeCancel="{{ route('base.user.index', ['userType' => $userType]) }}" isShow="true" />
                </div>
                <!--end::Main column-->
            </div>
        </div>
    </div>
</x-layouts.master-layout>
