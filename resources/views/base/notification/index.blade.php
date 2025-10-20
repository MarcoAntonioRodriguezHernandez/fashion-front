<x-layouts.master-layout title="Notificaciones" cardTitle="Notificaciones">

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Notificación</th>
                        <th class="min-w-125px">Enlace</th>
                        <th class="min-w-125px">Fecha</th>
                        <th class="min-w-125px">Orden</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $notification)
                        <tr>
                            <td>{{ $notification['id'] }}</td>
                            <td class="min-w-125px">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <!--begin::Title-->
                                        @php
                                            $text = $notification['text'];
                                            $decoded = null;
                                            try {
                                                $decoded = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
                                            } catch (\Throwable $e) {
                                                $decoded = null;
                                            }
                                        @endphp
                                        <a href="{{ route('base.notifications.show', $notification['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">
                                            @if (is_array($decoded) && isset($decoded['resumen']))
                                                {{ $decoded['resumen'] }}
                                            @else
                                                {{ $text }}
                                            @endif
                                        </a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $notification->link }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $notification->date }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">
                                    {{ optional($notification->orderMarketplaceId?->client)->full_name ?? 'Sin cliente' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <div class="col my-5">
            {{ $data->links() }}
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Notifications-->

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
