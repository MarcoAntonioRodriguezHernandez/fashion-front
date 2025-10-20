<x-layouts.master-layout title="Editar Perfil" cardTitle="Editar {{ $data->name }}">

    <div class="form_padding">
        <!--begin::Form-->
        <form id="staffAdd" class="form d-flex flex-column flex-lg-row" action="{{ route('base.user.edit') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $data->id }}">

            <!--begin::Aside column-->
            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                <!--begin::Thumbnail settings-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Foto de perfil</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="text-center pt-0">
                        <!--begin::Image input-->
                        <!--begin::Image input placeholder-->
                        <style>
                            .image-input-placeholder {
                                background-image: url('assets/media/svg/files/blank-image.svg');
                            }

                            [data-bs-theme="dark"] .image-input-placeholder {
                                background-image: url('assets/media/svg/files/blank-image-dark.svg');
                            }
                        </style>
                        <!--end::Image input placeholder-->
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                            data-kt-image-input="true">
                            <!--begin::Preview existing avatar-->
                            <div class="image-input-wrapper w-250px h-250px"
                                style="background-image: url('{{ asset($data->photo ?: 'src/img/user-image.png') }}')"></div>
                            <!--end::Preview existing avatar-->
                            <!--begin::Label-->
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar imagen">
                                <i class="ki-outline ki-pencil fs-7"></i>
                                <!--begin::Inputs-->
                                <input type="file" name="new_photo" accept=".png, .jpg, .jpeg" />
                                <!--end::Inputs-->
                            </label>
                            <!--end::Label-->
                            <!--begin::Cancel-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Quitar imagen">
                                <i class="ki-outline ki-cross fs-2"></i>
                            </span>
                            <!--end::Cancel-->
                            <!--begin::Remove-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Quitar imagen">
                                <i class="ki-outline ki-cross fs-2"></i>
                            </span>
                            <!--end::Remove-->
                        </div>
                        <!--end::Image input-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Por favor suba su imagen. Se acepta solamente *.png, *.jpg and *.
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Thumbnail settings-->
            </div>
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
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Nombre</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="name" id="name"
                                        placeholder="Nombre" value="{{ old('name', $data->name) }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Apellido</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInput" name="last_name" id="last_name"
                                        placeholder="Apellido" value="{{ old('last_name', $data->last_name) }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Correo</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->email }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">Teléfono</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <x-layouts.inputs typeInput="justInputNumber" name="phone" id="phone"
                                        placeholder="Teléfono" value="{{ old('phone', $data->phone) }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                @if ($data->employeeDetail)
                                    <hr class="my-8">

                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Tienda</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="employee_detail[store_id]" id="store_id" class="form-select form-control"
                                            data-control="select2" data-hide-search="true" data-placeholder="" >
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}" @selected(old('employee_detail.store_id', $store->id) == $data->employeeDetail->store_id)>
                                                    {{ $store->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="form-label">Recibe Cancelaciones</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInputdisabled" value="{{ $data->employeeDetail->notify_cancellations ? 'Sí' : 'No' }}" disabled />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                @endif

                                @if ($data->clientDetail)
                                    <hr class="my-8">

                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Fecha de Nacimiento</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <x-layouts.inputs typeInput="justInput" name="client_detail[date_of_birth]" id="date_of_birth" typeofInput="date"
                                            placeholder="Fecha de Nacimiento" value="{{ old('client_detail.date_of_birth', $data->clientDetail->date_of_birth) }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Género</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="client_detail[gender]" id="gender" class="form-select form-control"
                                            data-control="select2" data-hide-search="true" data-placeholder="" >
                                            @foreach (Genders::getAllNames() as $value => $name)
                                                <option value="{{ $value }}" @selected(old('client_detail.gender', $value) == $data->clientDetail->gender)>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                @endif
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->
                    </div>
                    <!--end::Tab content-->
                    @if (Auth::user())
                        <x-btn-cancel-save routeCancel="{{ route('base.user.index', ['userType' => $userType]) }}"  
                        :module="ModuleAliases::USER"
                        />
                    @else
                        <div class="card-header d-flex justify-content-end">
                            <div class="card-toolbar">
                                <div class="d-flex flex-stack flex-wrap gap-5">
                                    <a id="cancelButton" href="{{ route('base.user.index', ['userType' => $userType ?? null]) }}"
                                        class="btn btn-primary">Salir</a>
                                    <!--end::Search-->
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
    {{-- code for the validations fields, is necesary use the id of the form in the GeneralForm.init('staffAdd') --}}
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/general-form.js') }}"></script>

        <script>
            let validations = [
                'name',
                'last_name',
                'store_id'
                'phone'

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