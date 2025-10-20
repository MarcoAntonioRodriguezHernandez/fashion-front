<x-layouts.master-layout title="Tipos de Evento" cardTitle="Tipos de Evento">
    <x-layouts.card-header :module="ModuleAliases::EVENT_TYPE" :createRoute="route('base.event_types.create.view')" :createText="__('Agregar Tipo de Evento')" />

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
                        <th class="min-w-125px">Slug</th>
                        @permission(ModuleAliases::EVENT_TYPE, PermissionTypes::UPDATE)
                        <th class="min-w-125px text-start">Acciones</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $eventType)
                        <tr>
                            <td>{{ $eventType['id'] }}</td>
                            <td>
                                <span class="fw-bold">{{ $eventType->name }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $eventType->slug }}</span>
                            </td>
                            <x-actions-btn :module="ModuleAliases::EVENT_TYPE" routeEdit="{{ route('base.event_types.edit.view', $eventType['id']) }}" onclickDelete="eliminar({{ $eventType->id }})" />
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
    <x-slot name="js">
        <x-sweet-alert 
            title="¿Estas seguro que deseas eliminar este Tipo de Evento?"
            route="{{ route('base.event_types.delete', '') }}"
            successTitle="El tipo de evento ha sido eliminado"
            successText="El tipo de evento ha sido eliminado exitosamente"
            errorTitle="Error al eliminar el tipo de evento"
            errorText="Error al eliminar, intenta nuevamente" />
    </x-slot>

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</x-layouts.master-layout>
