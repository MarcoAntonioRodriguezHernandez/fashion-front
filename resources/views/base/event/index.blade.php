<x-layouts.master-layout title="Eventos" cardTitle="Eventos">
    <x-layouts.card-header :module="ModuleAliases::EVENT" />

    <!--begin::Events-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table" data-paging="false">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Tipo de Evento</th>
                        <th class="min-w-125px">Anfitrión/Sede</th>
                        <th class="min-w-125px">Fecha Programada</th>
                        @permission(ModuleAliases::EVENT, PermissionTypes::UPDATE)
                            <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $event)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $event->eventType->name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $event->specification }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $event->scheduled_date }}</span>
                            </td>
                            <x-actions-btn :module="ModuleAliases::EVENT" onclickDelete="eliminar({{ $event->id }})" />
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
    <!--end::Events-->

    <x-slot name="js">
        <x-sweet-alert
            title="¿Estas seguro que deseas eliminar este evento?"
            route="{{ route('base.events.delete', '') }}"
            successTitle="El evento ha sido eliminado"
            successText="El evento ha sido eliminado exitosamente"
            errorTitle="Error al eliminar el evento"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
