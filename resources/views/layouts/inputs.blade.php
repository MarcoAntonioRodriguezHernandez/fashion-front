@props([
    'type' => null,
    'id' => null,
    'name' => '',
    'placeholder' => '',
    'value' => '',
    'areaLabel' => '',
    'tooltiptitle' => '',
    'label' => '',
    'typeInput' => '',
    'typeofInput' => '',
    'options' => [],
    'selectedValue' => '',
    'namelabel' => '',
    'maxlength' => '',
    'min' => '',
    'max' => '',
    'disabled' => false,
])

@if ($typeInput === 'justInput')
    <input {{ $attributes }} type="{{ $typeofInput }}" name="{{ $name }}" id="{{ $id ?? $name }}"
        class="form-control mb-2" placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" required
        @disabled($disabled) />
@endif
@if ($typeInput === 'justInputNumber')
    <input {{ $attributes }} type="text" name="{{ $name }}" id="{{ $id ?? $name }}"
        maxlength="10" minlength="10" class="form-control mb-2"
        placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" required @disabled($disabled)
        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" />
@endif
@if ($typeInput === 'justInputNumberCoords')
    <input {{ $attributes }} type="text" name="{{ $name }}" id="{{ $id ?? $name }}"
    class="form-control mb-2"
    placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" required @disabled($disabled)
    oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(?!^)-/g, '').replace(/(\..*)\./g, '$1');  "
 />

@endif

@if ($typeInput === 'justInputdisabled')
    <input {{ $attributes }} type="{{ $typeofInput }}" name="{{ $name }}" id="{{ $id ?? $name }}"
        class="form-control mb-2" placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" disabled />
@endif

{{-- select in form  --}}
@if ($typeInput === 'justSelect')
    <select name="{{ $name }}" data-control="select1" data-placeholder="{{ $placeholder }}"
        id="{{ $name }}" class="form-select form-select-solid fw-bold">
        {{-- <option value="">Select a {{$namelabel}}</option> --}}
        @foreach ($options as $value => $label)
            <option value="{{ $value }}" {{ old($name, $selectedValue) == $value ? 'selected' : '' }}>
                {{ $label }}</option>
        @endforeach
    </select>
@endif

@if ($type === 'text')
    <label class="required fs-6 fw-semibold mb-2" for="{{ $name }}">{{ $label }}</label>
    <input {{ $attributes }} type="text" class="form-control form-control-solid" id="{{ $name }}"
        placeholder="{{ $placeholder }}" name="{{ $name }}" value="{{ old($name, $value) }}" />
@elseif($type === 'select')
    <label class="required fs-6 fw-semibold mb-2" for="{{ $name }}">
        <span class="required">{{ $name }}</span>
        <span class="ms-1" data-bs-toggle="tooltip" title="{{ $tooltiptitle }}">
            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
        </span>
    </label>
    <select name="{{ $name }}" aria-label="{{ $areaLabel }}" data-control="select2"
        data-placeholder="{{ $placeholder }}" id="{{ $name }}" data-dropdown-parent="#kt_modal_add_customer"
        class="form-select form-select-solid fw-bold">
        <option value="{{ old($name, $value) }}">Select a Country...</option>
    </select>
@endif

@if ($type === 'email')
    <label class="fs-6 fw-semibold mb-2" for="{{ $name }}">
        <span class="required">Email</span>
        <span class="ms-1" data-bs-toggle="tooltip" title="{{ $tooltiptitle }}">
            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
        </span>
    </label>
    <input type="email" class="form-control form-control-solid" placeholder="{{ $placeholder }}"
        name="{{ $name }}" value="{{ old($name, $value) }}" id="{{ $name }}" />
@elseif($type === 'password')
    <input type="password" class="form-control form-control-solid" placeholder="{{ $placeholder }}"
        name="{{ $name }}" value="{{ old($name, $value) }}" id="{{ $name }}" />
@endif

@if ($type === 'textarea')
    <label class="required fs-6 fw-semibold mb-2" for="{{ $name }}">{{ $label }}</label>
    <textarea name="{{ $name }}" class="form-control form-control-solid" rows="3"
        placeholder="{{ $placeholder }}" id="{{ $name }}">{{ old($name, $value) }}</textarea>
@endif

@if ($type === 'toogle')
    <input class="form-check-input" name="{{ $name }}" type="checkbox" value="{{ old($name, $value) }}"
        id="kt_modal_add_customer_billing" checked="checked" id="{{ $name }}" />
@elseif($type === 'checkbox')
    <input class="form-check-input" type="checkbox" data-kt-check="true"
        data-kt-check-target="#kt_customers_table .form-check-input" id="{{ $name }}"
        value="{{ old($name, $value) }}" />
@endif

@if ($typeInput === 'date')
    <input type="{{ $typeofInput }}" name="{{ $name }}" id="{{ $name }}" class="form-control mb-2"
        placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" required />
@endif

@if ($typeInput === 'dateDisabled')
    <input type="{{ $typeofInput }}" name="{{ $name }}" id="{{ $name }}"
        class="form-control mb-2" placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" disabled />
@endif

@if ($typeInput === 'imageUser')
    <div class="card card-flush py-4">
        <!--begin::Card header-->
        <div class="card-header">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>Imagen</h2>
            </div>
            <!--end::Card title-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body text-center pt-0">
            <!--begin::Image input-->
            <!--begin::Image input placeholder-->
            {{-- <style>
            .image-input-placeholder {
                background-image: url('assets/media/svg/files/blank-image.svg');
            }

            [data-bs-theme="dark"] .image-input-placeholder {
                background-image: url('assets/media/svg/files/blank-image-dark.svg');
            }
        </style> --}}
            <!--end::Image input placeholder-->
            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                data-kt-image-input="true">
                <!--begin::Preview existing avatar-->
                <div class="image-input-wrapper w-150px h-150px"
                    style="background-image: url({{ asset('media/avatars/blank.png') }})"></div>
                <!--end::Preview existing avatar-->
                <!--begin::Label-->
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar imagen">
                    <i class="ki-outline ki-pencil fs-7"></i>
                    <!--begin::Inputs-->
                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                    <input type="hidden" name="avatar_remove" />
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
            <div class="text-muted fs-7">Por favor suba su imagen. Se acepta solamente *.png, *.jpg and *.</div>
            <!--end::Description-->
        </div>
        <!--end::Card body-->
    </div>
@endif

@if ($typeInput === 'imageBig')
    <div class="card card-flush py-4">
        <!--begin::Card header-->
        <div class="card-header">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>Imagen</h2>
            </div>
            <!--end::Card title-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body text-center pt-0">
            <!--begin::Image input-->
            <!--begin::Image input placeholder-->
            {{-- <style>
            .image-input-placeholder {
                background-image: url('assets/media/svg/files/blank-image.svg');
            }

            [data-bs-theme="dark"] .image-input-placeholder {
                background-image: url('assets/media/svg/files/blank-image-dark.svg');
            }
        </style> --}}
            <!--end::Image input placeholder-->
            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                data-kt-image-input="true">
                <!--begin::Preview existing avatar-->
                <div class="image-input-wrapper w-250px h-350px"
                    style="background-image: url({{ asset('media/avatars/blank.png') }})"></div>
                <!--end::Preview existing avatar-->
                <!--begin::Label-->
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar imagen">
                    <i class="ki-outline ki-pencil fs-7"></i>
                    <!--begin::Inputs-->
                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                    <input type="hidden" name="avatar_remove" />
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
            <div class="text-muted fs-7">Por favor suba su imagen. Se acepta solamente *.png, *.jpg and *.</div>
            <!--end::Description-->
        </div>
        <!--end::Card body-->
    </div>
@endif
