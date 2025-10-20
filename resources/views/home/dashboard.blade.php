<x-layouts.master-layout :title="config('app.name')" :cardTitle="config('app.name')">

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
        data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
        data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
        data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <!--end::Toolbar-->
                <!--begin::Content-->
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <!--begin::Content container-->
                    <div id="kt_app_content_container" class="app-container container-fluid">
                        <!--begin::Row-->
                        <div class="row g-5 g-xl-10 mb-xl-10">
                            <!--begin::Col-->
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                                <!--begin::Card widget 4-->
                                <div class="card card-flush py-3 mb-5 mb-xl-10">
                                    <div class="card-header pt-5">
                                        <div class="card-title d-flex flex-column">
                                            <div class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">Categorías</div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                        <div class="d-flex flex-center me-5 pt-2">
                                            <div id="kt_card_widget_4_chart" style="min-width: 70px; min-height: 70px"
                                                data-kt-size="70" data-kt-line="11">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column content-justify-center w-100">
                                            @foreach ($categories->take(4) as $category)
                                                <div class="d-flex align-items-center py-1">
                                                    <span class="bullet w-8px h-6px rounded-2"
                                                        style="background-color: {{ $category->color }};"></span>
                                                    <span
                                                        class="text-gray-500 fw-semibold ms-3">{{ $category->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!--end::Card widget 4-->
                                <!--begin::Card widget 5-->
                                <div class="card card-flush py-3 mb-5 mb-xl-10">
                                    <!--begin::Header-->
                                    <div class="card-header pt-5">
                                        <!--begin::Title-->
                                        <div class="card-title d-flex flex-column">
                                            <!--begin::Info-->
                                            <div class="d-flex align-items-center">
                                                <!--begin::Amount-->
                                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">1,836</span>
                                                <!--end::Amount-->
                                                <!--begin::Badge-->
                                                <span class="badge badge-light-danger fs-base">
                                                    <i
                                                        class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>2.2%</span>
                                                <!--end::Badge-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Subtitle-->
                                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Pedidos de este mes</span>
                                            <!--end::Subtitle-->
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Card body-->
                                    <div class="card-body d-flex align-items-end pt-0">
                                        <!--begin::Progress-->
                                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                                <span class="fw-bolder fs-6 text-gray-900">1,048 to Goal</span>
                                                <span class="fw-bold fs-6 text-gray-500">62%</span>
                                            </div>
                                            <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                                <div class="bg-success rounded h-8px" role="progressbar"
                                                    style="width: 62%;" aria-valuenow="50" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <!--end::Progress-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card widget 5-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                                <!--begin::Card widget 6-->
                                <div class="card card-flush py-3 mb-5 mb-xl-10 card-custom-height">
                                    <!--begin::Header-->
                                    <div class="card-header pt-2">
                                        <!--begin::Title-->
                                        <div class="card-title d-flex flex-column">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Badge (arriba)-->

                                                <!--end::Badge-->
                                                <!--begin::Row cantidad-->
                                                <div id="avg-sales-row" class="d-flex align-items-center flex-wrap">
                                                    <!--begin::Currency-->
                                                    <span
                                                        class="fs-4 fw-semibold text-gray-500 me-1 align-self-start">$</span>
                                                    <!--end::Currency-->
                                                    <!--begin::Amount-->
                                                    <span data-amount
                                                        class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2 text">{{ number_format($ventasHoy, 2) }}</span>
                                                    <!--end::Amount-->
                                                    @php
                                                        $cambio = round($porcentajeCambio, 2);
                                                        $esPositivo = $cambio > 0;
                                                        $esNegativo = $cambio < 0;
                                                    @endphp
                                                    <span id="avg-sales-badge"
                                                        class="badge fs-base mb-1 avg-badge--right {{ $esPositivo ? 'badge-light-success' : ($esNegativo ? 'badge-light-danger' : 'badge-light-secondary') }}">
                                                        <i
                                                            class="ki-outline fs-5 ms-n1 {{ $esPositivo ? 'ki-arrow-up text-success' : ($esNegativo ? 'ki-arrow-down text-danger' : 'ki-dash text-muted') }}"></i>
                                                        @if ($esPositivo)
                                                            +{{ $cambio }}%
                                                        @elseif($esNegativo)
                                                            {{ $cambio }}%
                                                        @else
                                                            0%
                                                        @endif
                                                    </span>
                                                </div>
                                                <!--end::Row cantidad-->
                                            </div>
                                            <!--end::Info-->

                                            <!--begin::Subtitle-->
                                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Promedio de ventas
                                                diarias</span>
                                            <!--end::Subtitle-->
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Card body-->
                                    <div class="card-body d-flex flex-column align-items-center px-0 pb-0"
                                        style="margin-bottom: -10px;">
                                        <!--begin::Chart-->
                                        <div id="grafica_compras" class="grafica-estable"
                                            style="margin-top:-40px; padding-bottom: -px">
                                        </div>
                                        <!--end::Chart-->
                                        <div class="text-center pt-1"
                                            style="max-width: 100%; margin-top: -29px; word-wrap: break-word;">
                                            <span class="text-muted fw-semibold fs-6">{{ $textoSemana }}</span>
                                        </div>
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card widget 6-->
                                <!--begin::Card widget 7-->
                                <div class="card card-flush py-3 mb-5 mb-xl-10">
                                    <!--begin::Header-->
                                    <div class="card-header pt-5">
                                        <!--begin::Title-->
                                        <div class="card-title d-flex flex-column">
                                            <!--begin::Amount-->
                                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">6.3k</span>
                                            <!--end::Amount-->
                                            <!--begin::Subtitle-->
                                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Nuevos clientes este
                                                mes</span>
                                            <!--end::Subtitle-->
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Card body-->
                                    <div class="card-body d-flex flex-column justify-content-end pe-0">
                                        <!--begin::Title-->
                                        <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Clientes de hoy</span>
                                        <!--end::Title-->
                                        <!--begin::Users group-->
                                        <div class="symbol-group symbol-hover flex-nowrap">
                                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                                                title="Alan Warden">
                                                <span
                                                    class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
                                            </div>
                                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                                                title="Michael Eberon">
                                                <img alt="Pic" src="{{ asset('media/avatars/300-2.jpg') }}" />
                                            </div>
                                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                                                title="Susan Redwood">
                                                <span
                                                    class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
                                            </div>
                                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                                                title="Melody Macy">
                                                <img alt="Pic" src="{{ asset('media/avatars/300-4.jpg') }}" />
                                            </div>
                                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                                                title="Perry Matthew">
                                                <span
                                                    class="symbol-label bg-danger text-inverse-danger fw-bold">P</span>
                                            </div>
                                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                                                title="Barry Walter">
                                                <img alt="Pic" src="{{ asset('media/avatars/300-8.jpg') }}" />
                                            </div>
                                            <a href="#" class="symbol symbol-35px symbol-circle"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
                                                <span
                                                    class="symbol-label bg-light text-gray-400 fs-8 fw-bold">+42</span>
                                            </a>
                                        </div>
                                        <!--end::Users group-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card widget 7-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0">
                                <!--begin::Chart widget 3-->
                                <div class="card card-flush py-3 mb-5 mb-xl-10">
                                    <!--begin::Header-->
                                    <div class="card-header py-5">
                                        <!--begin::Title-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-gray-900">Ventas este mes</span>
                                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Todas las ventas</span>
                                        </h3>
                                        <!--end::Title-->
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <!--begin::Menu-->
                                            <button
                                                class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                                data-kt-menu-overflow="true">
                                                <i class="ki-outline ki-dots-square fs-1"></i>
                                            </button>
                                            <!--begin::Menu 2-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                                                data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">
                                                        Acciones</div>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu separator-->
                                                <div class="separator mb-3 opacity-75"></div>
                                                <!--end::Menu separator-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <div class="menu-content px-3 py-3">
                                                        <a class="btn btn-primary btn-sm px-4" href="#">Generar
                                                            Reporte</a>
                                                    </div>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu 2-->
                                            <!--end::Menu-->
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Card body-->
                                    <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                                        <!--begin::Statistics-->
                                        <div class="px-9 mb-5">
                                            <!--begin::Statistics-->
                                            <div class="d-flex mb-2">
                                                <span class="fs-4 fw-semibold text-gray-500 me-1">$</span>
                                                <span
                                                    class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">14,094</span>
                                            </div>
                                            <!--end::Statistics-->
                                            <!--begin::Description-->
                                            <span class="fs-6 fw-semibold text-gray-500">Otros 48.346 $ para alcanzar
                                                el objetivo</span>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Statistics-->
                                        <!--begin::Chart-->
                                        <div id="kt_charts_widget_3" class="min-h-auto ps-4 pe-6"
                                            style="height: 300px"></div>
                                        <!--end::Chart-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Chart widget 3-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row gy-5 g-xl-10">
                            <!--begin::Col-->
                            <div class="col-xl-4 mb-xl-10">
                                <!--begin::Engage widget 1-->
                                <div class="card h-md-100" dir="ltr">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column flex-center">
                                        <!--begin::Heading-->
                                        <div class="mb-2">
                                            <!--begin::Title-->
                                            <h1 class="fw-semibold text-gray-800 text-center lh-lg">Necesitas agregar
                                                un
                                                <br />nuevo
                                                <span class="fw-bolder">producto?</span>
                                            </h1>
                                            <!--end::Title-->
                                            <!--begin::Illustration-->
                                            <div class="py-10 text-center">
                                                <img src="{{ asset('media/illustrations/dozzy-1/3-dark.png') }}"
                                                    class="theme-light-show w-200px" alt="" />
                                                <img src="{{ asset('media/illustrations/dozzy-1/3-dark.png') }}"
                                                    class="theme-dark-show w-200px" alt="" />
                                            </div>
                                            <!--end::Illustration-->
                                        </div>
                                        <!--end::Heading-->
                                        <!--begin::Links-->
                                        <div class="text-center mb-1">
                                            <!--begin::Link-->
                                            <a class="btn btn-sm btn-primary me-2"
                                                href="{{ route('base.product.full_create.view') }}">Nuevo Producto</a>
                                            <!--end::Link-->
                                        </div>
                                        <!--end::Links-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Engage widget 1-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-xl-8 mb-5 mb-xl-10">
                                <!--begin::Table Widget 4-->
                                <div class="card card-flush h-xl-100">
                                    <!--begin::Card header-->
                                    <div class="card-header pt-7">
                                        <!--begin::Title-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-gray-800">Ordenes</span>
                                        </h3>
                                        <!--end::Title-->
                                        <!--begin::Actions-->
                                        <div class="card-toolbar">
                                            <!--begin::Filters-->
                                            <div class="d-flex flex-stack flex-wrap gap-4">
                                                <!--begin::Status-->
                                                <div class="d-flex align-items-center fw-bold">
                                                    <!--Inicio::Etiqueta-->
                                                    <div class="text-gray-500 fs-7 me-2">Estatus</div>
                                                    <!--End::Label-->
                                                    <!--Home::Select-->
                                                    <select
                                                        class="form-select form-select-transparent text-gray-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto"
                                                        data-control="select2"
                                                        data-placeholder="Selecciona una opción"
                                                        data-hide-search="true" data-dropdown-css-class="w-150px"
                                                        id="data-table-statusFilter"
                                                        onchange="updateFilters('data-table')">
                                                        <option value="all" selected>Mostrar Todo</option>
                                                        <option value="1">Activo</option>
                                                        <option value="0">Inactivo</option>
                                                    </select>
                                                    <!--End::Select-->
                                                </div>
                                                <!--end::Status-->
                                            </div>
                                            <!--begin::Filters-->
                                        </div>
                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-2">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-3" id="data-table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="min-w-100px">ID de Orden</th>
                                                    <th class="text-end min-w-100px">Creado</th>
                                                    <th class="text-end min-w-125px">Cliente</th>
                                                    <th class="text-end min-w-100px">Total</th>
                                                    <th class="text-end min-w-100px">Ganancia</th>
                                                    <th class="text-end min-w-50px">Estatus</th>
                                                    <th class="text-end"></th>
                                                </tr>
                                            </thead>
                                            <!--begin::Table body-->
                                            <tbody class="fw-bold text-gray-600">
                                                @foreach ($orders as $order)
                                                    <tr class="order-row" data-store="{{ $order->store_id }}"
                                                        data-status="{{ $order->status == 1 ? '1' : '0' }}">
                                                        <td>
                                                            <a class="fw-bold"
                                                                href="{{ route('marketplace.order_marketplace.show', $order->id) }}">#{{ $order->id }}</a>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $order->created_at->diffForHumans() }}</td>
                                                        <td class="text-end">{{ $order->client->name }}</td>
                                                        <td class="text-end">
                                                            ${{ number_format($order->amount_total, 2) }}</td>
                                                        <td class="text-end">
                                                            ${{ number_format($order->amount_total, 2) }}
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($order->status == 1)
                                                                <div class="badge badge-light-success fw-bold">
                                                                    {{ __('Activo') }}</div>
                                                            @else
                                                                <div class="badge badge-light-danger fw-bold">
                                                                    {{ __('Inactivo') }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="text-end"></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card body-->
                            </div>


                        </div>
                    </div>

                    <!--end::Table Widget 4-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row gy-5 g-xl-10">
                <!--begin::Col-->
                <div class="col-xl-4">
                    <!--begin::List widget 5-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Header-->
                        <div class="card-header pt-7">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900">Productos más hot</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-6">
                                    <!--begin::Dropdown-->
                                    <select class="form-select form-select-sm form-select-solid" id="saleTypeFilter"
                                        onchange="filterProducts()">
                                        <option value="sale">Top 10 Vendidos</option>
                                        <option value="rent" selected>Top 10 Rentados</option>
                                    </select>
                                    <!--end::Dropdown-->
                                </span>
                            </h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Scroll-->
                            <div class="hover-scroll-overlay-y pe-6 me-n6" style="height: 415px">
                                <div id="saleProducts" style="display: none;">
                                    @foreach ($sale as $product)
                                        <!--begin::Item-->
                                        <div class="border border-dashed border-gray-300 rounded px-7 py-3 mb-6">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-stack mb-3">
                                                <!--begin::Wrapper-->
                                                <div class="me-3">
                                                    <!--begin::Icon-->
                                                    <img src="{{ asset($product->firstImage->src_image) }}"
                                                        class="w-50px ms-n1 me-1" alt="" />
                                                    <!--end::Icon-->
                                                    <!--begin::Title-->
                                                    <a href="{{ route('base.product.show', $product->id) }}"
                                                        class="text-gray-800 text-hover-primary fw-bold">{{ $product->name }}</a>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Wrapper-->
                                                <!--begin::Action-->
                                                <div class="m-0">
                                                    <!--begin::Menu-->
                                                    <button
                                                        class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                                        data-kt-menu-trigger="click"
                                                        data-kt-menu-placement="bottom-end"
                                                        data-kt-menu-overflow="true">
                                                        <i class="ki-outline ki-dots-square fs-1"></i>
                                                    </button>
                                                    <!--begin::Menu 2-->
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                                                        data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <div
                                                                class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">
                                                                Acciones</div>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu separator-->
                                                        <div class="separator mb-3 opacity-75"></div>
                                                        <!--end::Menu separator-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('base.product.show', $product->id) }}"
                                                                class="menu-link px-3">Detalles</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('base.item.index') }}"
                                                                class="menu-link px-3">Ver Artículos</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu 2-->
                                                    <!--end::Menu-->
                                                </div>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Customer-->
                                            <div class="d-flex flex-stack">
                                                <!--begin::Name-->
                                                <span class="text-gray-500 fw-bold">Ventas:
                                                    <a href="#"
                                                        class="text-gray-800 text-hover-primary fw-bold">
                                                        {{ number_format($product->total_sales) }}
                                                    </a>
                                                </span>
                                                <!--end::Name-->
                                                <!--begin::Label-->
                                                <span
                                                    class="badge badge-light-{{ ItemStatuses::getColor($product->status) }} fw-bold">
                                                    {{ ItemStatuses::getName($product->status) }}
                                                </span>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Customer-->
                                        </div>
                                        <!--end::Item-->
                                    @endforeach
                                </div>

                                <div id="rentProducts">
                                    @foreach ($rented as $product)
                                        <!--begin::Item-->
                                        <div class="border border-dashed border-gray-300 rounded px-7 py-3 mb-6">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-stack mb-3">
                                                <!--begin::Wrapper-->
                                                <div class="me-3">
                                                    <!--begin::Icon-->
                                                    <img src="{{ asset($product->firstImage->src_image) }}"
                                                        class="w-50px ms-n1 me-1" alt="" />
                                                    <!--end::Icon-->
                                                    <!--begin::Title-->
                                                    <a href="{{ route('base.product.show', $product->id) }}"
                                                        class="text-gray-800 text-hover-primary fw-bold">{{ $product->name }}</a>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Wrapper-->
                                                <!--begin::Action-->
                                                <div class="m-0">
                                                    <!--begin::Menu-->
                                                    <button
                                                        class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                                        data-kt-menu-trigger="click"
                                                        data-kt-menu-placement="bottom-end"
                                                        data-kt-menu-overflow="true">
                                                        <i class="ki-outline ki-dots-square fs-1"></i>
                                                    </button>
                                                    <!--begin::Menu 2-->
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                                                        data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <div
                                                                class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">
                                                                Acciones</div>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu separator-->
                                                        <div class="separator mb-3 opacity-75"></div>
                                                        <!--end::Menu separator-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('base.product.show', $product->id) }}"
                                                                class="menu-link px-3">Detalles</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('base.item.index') }}"
                                                                class="menu-link px-3">Ver Artículos</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu 2-->
                                                    <!--end::Menu-->
                                                </div>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Customer-->
                                            <div class="d-flex flex-stack">
                                                <!--begin::Name-->
                                                <span class="text-gray-500 fw-bold">Rentas:
                                                    <a href="#"
                                                        class="text-gray-800 text-hover-primary fw-bold">
                                                        {{ number_format($product->total_sales) }}
                                                    </a>
                                                </span>
                                                <!--end::Name-->
                                                <!--begin::Label-->
                                                <span
                                                    class="badge badge-light-{{ ItemStatuses::getColor($product->status) }} fw-bold">
                                                    {{ ItemStatuses::getName($product->status) }}
                                                </span>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Customer-->
                                        </div>
                                        <!--end::Item-->
                                    @endforeach
                                </div>
                            </div>
                            <!--end::Scroll-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::List widget 5-->

                    <script>
                        function filterProducts() {
                            const filter = document.getElementById('saleTypeFilter').value;
                            const saleDiv = document.getElementById('saleProducts');
                            const rentDiv = document.getElementById('rentProducts');

                            if (filter === 'sale') {
                                saleDiv.style.display = 'block';
                                rentDiv.style.display = 'none';
                            } else {
                                saleDiv.style.display = 'none';
                                rentDiv.style.display = 'block';
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            const savedFilter = sessionStorage.getItem('productFilter');
                            if (savedFilter) {
                                document.getElementById('saleTypeFilter').value = savedFilter;
                            }

                            filterProducts();

                            document.getElementById('saleTypeFilter').addEventListener('change', function() {
                                sessionStorage.setItem('productFilter', this.value);
                                filterProducts();
                            });
                        });
                    </script>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-8">
                    <!--begin::Table Widget 5-->
                    <div id="stock-card" class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        @php
                            $hasStoreFilter = request()->filled('store_id');
                        @endphp

                        <div class="card-header pt-7">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900">Informe de Stock en <span
                                        id="selected-store-name">{{ $selectedStoreName ?? 'Almacén' }}</span>
                                </span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-6" id="total-stock">
                                    Total de {{ number_format($totalStock) }}
                                    Artículos en el Stock
                                </span>
                            </h3>
                            <!--end::Title-->
                            <div class="card-toolbar ms-auto">
                                <div id="storeFilterWrap" class="d-flex flex-column align-items-end"
                                    style="min-width: 240px;">
                                    <label for="store"
                                        class="form-label fw-bold mb-1 w-100 text-gray-700 justify-content-center d-flex">
                                        Filtrar por sucursal
                                    </label>
                                    <select name="store_id" id="store" class="form-select form-select-sm w-100"
                                        data-warehouse-id="{{ \App\Models\Base\Store::where('name', 'Almacén')->value('id') }}">
                                        <option value="" {{ $hasStoreFilter ? '' : 'selected' }} disabled>
                                            Selecciona sucursal</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}"
                                                {{ $hasStoreFilter && (int) request('store_id') === (int) $store->id ? 'selected' : '' }}>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="px-9 pt-0 pb-3">
                            <div class="stock-search-wrap">
                                <form class="w-100" onsubmit="return false;">
                                    <div class="position-relative">
                                        <i
                                            class="ki-outline ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4"></i>
                                        <input id="stockSearch" name="search" type="text"
                                            class="form-control form-control-solid ps-12"
                                            placeholder="Buscar producto..." value="{{ request('search') }}"
                                            autocomplete="off" />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                            <div class="hover-scroll-overlay-y pe-6 me-n6" style="height: 415px">
                                <!--begin::Table-->
                                <table id="kt_table_widget_5_table"
                                    class="table align-middle table-row-dashed fs-6 gy-3">
                                    <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-150px">Nombre</th>
                                            <th class="min-w-125px text-end">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                        @foreach ($almacenProducts as $product)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('base.product.show', $product->id) }}"
                                                        class="text-gray-800 text-hover-primary fw-bold">{{ $product->name }}</a>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#" class="text-primary" data-bs-toggle="modal"
                                                        data-bs-target="#variantModal"
                                                        data-product="{{ $product->id }}"
                                                        data-product-name="{{ $product->name }}"
                                                        data-store-id="{{ $selectedStoreId }}">
                                                        {{ $product->almacen_count }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-4 sticky-pagination">
                                    {{ $almacenProducts->links() }}
                                </div>

                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Table Widget 5-->
                </div>
                <!--end::Col-->

            </div>
            <!--end::Row-->
        </div>
        <!--end::Content container-->
        </div>
        <!--end::Content-->
        </div>
        <!--end::Wrapper-->
        <!--end::Page-->
        <!--begin::Javascript-->
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <!--end::Custom Javascript-->
        <!--begin::Custom Javascript - Search functionality for orders table-->

        <style>
            .hover-scroll-overlay-y {
                position: relative;
            }

            .sticky-pagination {
                position: sticky;
                bottom: 0;
                background: var(--bs-body-bg);
                padding-top: .5rem;
                box-shadow: 0 -4px 8px rgba(0, 0, 0, .03);
                z-index: 2;
            }

            #grafica_compras.grafica-estable {
                width: 100%;
                min-height: 1430px;
            }

            #avg-sales-row {
                gap: .25rem .5rem;
            }

            #avg-sales-badge.avg-badge--right {
                margin-left: .25rem;
                inline-size: max-content;
            }

            #avg-sales-badge.avg-badge--below {
                display: block;
                width: auto;
                text-align: center;
                margin: .25rem auto 0;
                padding-round: .5rem;
            }

            .card.card-custom-height .card-body {
                padding-bottom: .75rem !important;
            }

            .card.card-custom-height .card-body .text-center.pt-1 {
                display: inline-block;
                margin-bottom: .5rem;
            }

            #stock-card .stock-search-wrap {
                max-width: 420px;
                width: 100%;
            }

            #stockSearch::placeholder {
                color: var(--bs-gray-500);
            }
        </style>
        <div class="modal fade" id="variantModal" tabindex="-1" aria-labelledby="variantModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="variantModalTitle">Variantes del producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" id="variantModalBody">
                        Cargando...
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function() {
                function placeAvgBadge() {
                    const row = document.getElementById('avg-sales-row');
                    const badge = document.getElementById('avg-sales-badge');
                    const amount = row?.querySelector('[data-amount]');
                    if (!row || !badge || !amount) return;

                    badge.classList.remove('avg-badge--below');
                    badge.classList.add('avg-badge--right');

                    const rr = row.getBoundingClientRect();
                    const br = badge.getBoundingClientRect();
                    const ar = amount.getBoundingClientRect();

                    const overflowRight = br.right > rr.right;
                    const wrappedLine = br.top > ar.top + 1;

                    if (overflowRight || wrappedLine) {
                        badge.classList.remove('avg-badge--right');
                        badge.classList.add('avg-badge--below');
                    }
                }

                window.addEventListener('DOMContentLoaded', placeAvgBadge);
                window.addEventListener('resize', placeAvgBadge);
            })();
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const card = document.getElementById('stock-card');
                if (!card) return;

                const scrollWrap = card.querySelector('.hover-scroll-overlay-y');
                const storeSelect = card.querySelector('#store');
                const titleStore = card.querySelector('#selected-store-name');
                const totalStock = card.querySelector('#total-stock');
                const searchInput = document.getElementById('stockSearch');
                const clearBtn = document.getElementById('stockSearchClear');
                const buildUrl = (base, storeId, page = null, q = '') => {
                    const u = new URL(base, window.location.origin);
                    if (storeId) u.searchParams.set('store_id', storeId);
                    else u.searchParams.delete('store_id');
                    if (page) u.searchParams.set('page', page);
                    else u.searchParams.delete('page');
                    q = (q || '').trim();
                    if (q !== '') u.searchParams.set('search', q);
                    else u.searchParams.delete('search');
                    return u.toString();
                };

                const reloadStock = async (url) => {
                    if (!scrollWrap) return;
                    scrollWrap.style.opacity = .6;
                    try {
                        const resp = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const html = await resp.text();
                        const doc = new DOMParser().parseFromString(html, 'text/html');

                        const newTable = doc.querySelector('#kt_table_widget_5_table');
                        const newPager = doc.querySelector('.d-flex.justify-content-end.mt-4');
                        const newTotal = doc.querySelector('#total-stock');

                        const curTable = card.querySelector('#kt_table_widget_5_table');
                        const curPager = card.querySelector('.d-flex.justify-content-end.mt-4');

                        if (newTable && curTable) curTable.replaceWith(newTable);
                        if (newPager && curPager) {
                            newPager.classList.add('sticky-pagination');
                            curPager.replaceWith(newPager);
                        }
                        if (newTotal && totalStock) totalStock.innerHTML = newTotal.innerHTML;
                        if (titleStore) {
                            if (storeSelect && storeSelect.value) {
                                titleStore.textContent = storeSelect.options[storeSelect.selectedIndex].text;
                            } else {
                                titleStore.textContent = 'Almacén';
                            }
                        }
                    } finally {
                        scrollWrap.style.opacity = 1;
                    }
                };

                if (storeSelect) {
                    storeSelect.addEventListener('change', () => {
                        if (!storeSelect.value) return;
                        const url = buildUrl(
                            window.location.pathname,
                            storeSelect.value,
                            null,
                            searchInput ? searchInput.value : ''
                        );
                        reloadStock(url);
                    });
                    const ph = storeSelect.querySelector('option[value=""]');
                    if (ph) storeSelect.addEventListener('change', () => {
                        ph.disabled = true;
                    }, {
                        once: true
                    });
                }

                if (searchInput) {
                    const form = searchInput.closest('form');
                    if (form) form.addEventListener('submit', e => e.preventDefault());
                    searchInput.addEventListener('keydown', e => {
                        if (e.key === 'Enter') e.preventDefault();
                    });

                    let t;
                    searchInput.addEventListener('input', () => {
                        clearTimeout(t);
                        if (clearBtn) clearBtn.style.display = searchInput.value.trim() ? 'inline-flex' :
                            'none';
                        t = setTimeout(() => {
                            const url = buildUrl(
                                window.location.pathname,
                                storeSelect ? storeSelect.value : null,
                                null,
                                searchInput.value
                            );
                            reloadStock(url);
                        }, 350);
                    });

                    if (clearBtn) {
                        clearBtn.addEventListener('click', () => {
                            searchInput.value = '';
                            clearBtn.style.display = 'none';
                            const url = buildUrl(
                                window.location.pathname,
                                storeSelect ? storeSelect.value : null,
                                null,
                                ''
                            );
                            reloadStock(url);
                        });
                    }
                }

                if (scrollWrap) {
                    scrollWrap.addEventListener('click', (e) => {
                        const a = e.target.closest('.pagination a');
                        if (!a) return;
                        e.preventDefault();
                        const clicked = new URL(a.getAttribute('href'), window.location.origin);
                        const page = clicked.searchParams.get('page');
                        const url = buildUrl(
                            window.location.pathname,
                            storeSelect ? storeSelect.value : null,
                            page,
                            searchInput ? searchInput.value : ''
                        );
                        reloadStock(url);
                    });
                }
            });
        </script>
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const fechasSemana = @json($labels);
                    const datosVentas = @json($data);

                    const options = {
                        series: [{
                            name: "Ventas",
                            data: datosVentas
                        }],
                        chart: {
                            type: "bar",
                            height: 130,
                            toolbar: {
                                show: false
                            },
                            fontFamily: "inherit"
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '50%',
                                borderRadius: 8
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        xaxis: {
                            categories: fechasSemana,
                            labels: {
                                show: false
                            },
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: false
                            }
                        },
                        yaxis: {
                            show: true,
                            labels: {
                                show: false
                            },
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: false
                            }
                        },
                        grid: {
                            yaxis: {
                                lines: {
                                    show: false
                                }
                            },
                            xaxis: {
                                lines: {
                                    show: true
                                }
                            }
                        },
                        tooltip: {
                            x: {
                                formatter: val => "Día: " + val
                            },
                            y: {
                                formatter: val => `$${Number(val).toFixed(2)}`
                            },
                            style: {
                                fontSize: "12px"
                            }
                        },
                        colors: ['#000000']
                    };

                    const contenedor = document.querySelector("#grafica_compras");
                    if (contenedor) {
                        const chart = new ApexCharts(contenedor, options);
                        chart.render();
                    }
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('variantModal');
                    modal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const productId = button.getAttribute('data-product');
                        const storeSelect = document.querySelector('#stock-card #store');
                        const storeId = storeSelect && storeSelect.value ? storeSelect.value : '';
                        const url = `/products/${productId}/variants${storeId ? `?store_id=${storeId}` : ''}`;
                        fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(r => r.text())
                            .then(html => {
                                document.getElementById('variantModalBody').innerHTML = html;
                                const name = button.getAttribute('data-product-name') || 'Producto';
                                document.getElementById('variantModalTitle').textContent =
                                    `Variantes de: ${name}`;
                            });
                    });
                });
            </script>
        @endpush
        @stack('scripts')
    </body>
</x-layouts.master-layout>
