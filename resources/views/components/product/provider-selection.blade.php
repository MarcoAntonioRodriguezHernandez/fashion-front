@props([
    'designers' => [],
    'providers' => [],
    'countries' => [],
])

<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card body-->
        <div class="card-body pt-0 mt-5">
            <div class="row mb-10" id="provider-selection">
                <!--begin::Input group-->
                <div class="col-md-12">
                    <!-- begin::Label-->
                    <label class="required form-label">Proveedor</label>
                    <!-- end::Label-->
                    <!-- begin::Select-->
                    <select name="provider_id" id="provider_id" class="form-select form-control" data-control="select2" data-hide-search="true" data-placeholder="">
                        <option selected hidden disabled>-- Elige una opción --</option>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider->id }}" @selected($provider->id == old('provider_id'))>
                                {{ $provider->name }}</option>
                        @endforeach
                    </select>
                    <!-- end::Select-->
                </div>
                <!--end::Input group-->

                <div class="mt-8 offset-9 col-md-3">
                    <div class="d-flex justify-content-end" style="margin-top: -15px; margin-left: 20px">
                        <!--begin::Link-->
                        <a onclick="showProviderForm()" class="btn btn-primary">Agregar Proveedor</a>
                        <!--end::Link-->
                    </div>
                </div>
            </div>
            <div class="row mb-10" id="provider-form" hidden>
                <h1 class="mb-10 text-center">Agregar nuevo proveedor</h1>
                <div class="col-md-6 mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Nombre</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="provider[name]" id="provider-name" placeholder="Nombre" value="{{ old('provider.name') }}" />
                    <!--end::Input-->
                </div>
                <div class="col-md-6 mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Contacto</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="provider[contact]" id="provider-contact" placeholder="Contacto" value="{{ old('provider.contact') }}" />
                    <!--end::Input-->

                </div>
                <div class="col-md-6 mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Correo</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="email" name="provider[email]" id="provider-email" placeholder="Correo" value="{{ old('provider.email') }}" />
                    <!--end::Input-->
                </div>
                <div class="col-md-6 mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Teléfono</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="provider[phone]" id="provider-phone" placeholder="Teléfono" value="{{ old('provider.phone') }}" />
                    <!--end::Input-->
                </div>
                <div class="col-md-6 mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Página Web</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <x-layouts.inputs typeInput="justInputdisabled" typeofInput="text" name="provider[url]" id="provider-url" placeholder="Página Web" value="{{ old('provider.url') }}" />
                    <!--end::Input-->
                </div>
                <div class="col-md-6 mb-10">
                    <!--begin::Label-->
                    <label class="form-label">País</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <div class="d-flex gap-3">
                        <select name="provider[country_id]" id="provider-country_id" class="form-select form-control" data-control="select2" data-hide-search="false">
                            <option selected hidden disabled>-- Elige una opción --</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" @selected($country->id == old('provider.country_id'))>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Input-->
                </div>

                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="form-label">Marcas / Diseñadores</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <div class="d-flex gap-3">
                        <select class="form-select select2" data-control="select2" data-tags="true" multiple name="provider[designers][]" disabled>
                            <option label="Label"></option>
                            @foreach ($designers as $designer)
                                <option value="{{ $designer->id }}" @selected(in_array($designer->id, old('provider.designers', [])))>
                                    {{ $designer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <div class="d-flex justify-content-end mt-5">
                    <button onclick="showProviderSelection()" id="btn-hideProvider" class="btn btn-primary" type="button">Seleccionar Existente</button>
                </div>
            </div>
        </div>
        <!--end::Input group-->
    </div>
</div>
