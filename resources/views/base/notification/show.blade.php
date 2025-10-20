<x-layouts.master-layout title="Notificación" cardTitle="Notificación">

    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <!--begin::Card header-->
        <!--begin::Card body-->
        <div class="card-body p-9">
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Descripcion:</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    @php
                        $textData = json_decode($data->text, true);
                    @endphp
                    @if (is_array($textData) && isset($textData['resumen']))
                        <span class="fw-bold fs-6 text-gray-800">{{ $textData['resumen'] }}</span>
                    @else
                        <span class="fw-bold fs-6 text-gray-800">{{ $data->text }}</span>
                    @endif
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Fecha:</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $data->date }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Link de Rastreo:</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    @php
                        $textData = json_decode($data->text, true);
                    @endphp
                    @if (is_array($textData) && isset($textData['links']))
                        <ul class="mb-0 list-unstyled">
                            @foreach ($textData['links'] as $link)
                                <li>
                                    <a href="{{ $link['url'] }}"
                                        class="fw-semibold fs-6 text-gray-800 text-hover-primary" target="_blank">
                                        Distribución #{{ $link['code'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ $data->link }}"
                            class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ $data->link }}</a>
                    @endif
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
</x-layouts.master-layout>
