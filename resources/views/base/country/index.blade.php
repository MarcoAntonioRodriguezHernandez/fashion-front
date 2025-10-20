<x-layouts.master-layout title="Catálogo de Países" cardTitle="Catágolo de Países">

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="max-w-125px">ID</th>
                        <th class="min-w-125px">Nombre</th>
                        <th class="min-w-125px">Código</th>
                        <th class="min-w-150px">Código del país</th>

                    </tr>
                </thead>
                {{-- Table --}}
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $country)
                        <tr>
                            <td>{{ $country['id'] }}</td>
                            <td>
                                <span class="fw-bold">{{ $country['name']}}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $country['code'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $country['code_number']}}</span>
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
    <!--end::Products-->

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>