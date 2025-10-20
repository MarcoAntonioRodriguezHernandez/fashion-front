@props(['locations', 'header', 'title','description'])

<div class="col-md-8">
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <div class="card-header pt-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">{{ $header }}</span>
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body d-flex justify-content-center">
                <!--begin::Map container-->
                <div class="col-12">
                    <div id="map" style="width: 100%; height: 400px;"></div>
                </div>
                <!--end::Map container-->
            </div>
            <!--end::Body-->
        </div>
    </div>
</div>
<div class="col-md-4">
    <!--begin::Chart widget 14-->
    <div class="card card-flush" style="height: 550px; overflow: auto;">
        <!--begin::Header-->
        <div class="card-header pt-7">
            <!--begin::Title-->
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-gray-800">{{ $title }}</span>
                <span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $description }}</span>
            </h3>
            <!--end::Title-->
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body align-items-end">
            <!--begin::Wrapper-->
            <div class="w-100">
                <!--begin::Item-->
                <div class="col-md-12 mb-10">
                    @foreach ($locations as $index => $location)
                        <div class="d-flex align-items-center random-color-icon location-item" data-table="{{ $index }}">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-30px me-5">
                                <span class="symbol-label">
                                    <i class="fa fa-map-marker fs-3"></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Container-->
                            <div class="d-flex align-items-center flex-stack flex-wrap d-grid gap-1 flex-row-fluid">
                                <!--begin::Content-->
                                <div class="me-5">
                                    <!--begin::Separator-->
                                    <div class="separator separator-dashed my-3"></div>
                                    <!--end::Separator-->
                                    <!--begin::Title-->
                                    <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">
                                        {{ $location['title'] }}
                                    </a>
                                    <!--end::Title-->
                                    <!--begin::Desc-->
                                    <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">
                                        {{ $location['description'] }}
                                    </span>
                                    <!--end::Desc-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Container-->
                        </div>
                    @endforeach
                </div>
                <!--end::Container-->
            </div>
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Body-->
</div>

<script>
    window.locations = @json($locations);
</script>

<script src="{{ asset('js/maps.js') }}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.maps.api_key') }}&callback=initMap" async defer></script>
