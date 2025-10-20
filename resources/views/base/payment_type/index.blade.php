<x-layouts.master-layout title="Tipos de pago" cardTitle="Tipos de pago">

    <x-layouts.card-header :module="ModuleAliases::PAYMENT_TYPE" :createRoute="route('base.payment_type.create.view')" :createText="__('Agregar tipo de pago')" />

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
                        @permission(ModuleAliases::PAYMENT_TYPE, PermissionTypes::UPDATE)
                            <th class="min-w-125px text-start">Acciones</th>
                            <th class="min-w-125px text-start">Estado</th>
                        @endpermission
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($data as $paymentType)
                        <tr>
                            <td>{{ $paymentType['id'] }}</td>
                            <td><a href="{{ route('base.payment_type.show', $paymentType['id']) }}">
                                    <span class="fw-bold">{{ $paymentType['name'] }}</span>
                                </a>
                            </td>

                            <x-actions-btn :module="ModuleAliases::PAYMENT_TYPE"
                                routeEdit="{{ route('base.payment_type.edit.view', $paymentType['id']) }}"
                                onclickDelete="eliminar({{ $paymentType->id }})" />

                            @permission(ModuleAliases::PAYMENT_TYPE, PermissionTypes::UPDATE)
                                <td class="text-start">
                                    <form method="POST"
                                        action="{{ route('base.payment_type.set-visibility', $paymentType['id']) }}">
                                        @csrf
                                        @method('PATCH')

                                        {{-- Cuando NO está marcado, mandamos HIDDEN (2) --}}
                                        <input type="hidden" name="visibility"
                                            value="{{ \App\Enums\PaymentTypeVisibilities::HIDDEN->value }}">

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="visibility"
                                                value="{{ \App\Enums\PaymentTypeVisibilities::VISIBLE->value }}"
                                                onchange="this.form.submit()" @checked(
                                                    ($paymentType->visibility ?? \App\Enums\PaymentTypeVisibilities::VISIBLE->value) ==
                                                        \App\Enums\PaymentTypeVisibilities::VISIBLE->value)>
                                        </div>
                                    </form>
                                </td>
                            @endpermission
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
        <x-sweet-alert title="¿Estas seguro que deseas eliminar este tipo de pago?"
            route="{{ route('base.payment_type.delete', '') }}" successTitle="El tipo de pago ha sido eliminado"
            successText="El tipo de pago ha sido eliminado exitosamente" errorTitle="Error al eliminar el tipo de pago"
            errorText="Error al eliminar, intenta nuevamente" />

        <script>
            document.addEventListener('change', async (e) => {
                if (!e.target.classList.contains('js-toggle-visibility')) return;

                const input = e.target;
                const url = input.dataset.url;
                const checked = input.checked;

                input.disabled = true;
                try {
                    const res = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    });
                    if (!res.ok) throw new Error(await res.text());
                    const data = await res.json();
                    if (!data.success) throw new Error('Respuesta inválida');
                } catch (err) {
                    input.checked = !checked; // revierte visualmente
                    if (window.Swal) Swal.fire('Error', 'No se pudo actualizar el estado', 'error');
                    console.error(err);
                } finally {
                    input.disabled = false;
                }
            });
        </script>
    </x-slot>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endsection
</x-layouts.master-layout>
