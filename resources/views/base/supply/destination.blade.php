<x-layouts.master-layout title="Distribuciones hacia {{ $store->name }}" cardTitle="Distribuciones hacia {{ $store->name }}">
    <div class="form_padding">
        <!--begin::Aside column-->
        <div class="form d-flex flex-column flex-lg-row"></div>
        <!--end::Aside column-->
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <!--end:::Tabs-->
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <!--begin::Inventory-->
                @forelse ($data as $supplyTransfer)
                    <x-supply.transfer-store :origin="$supplyTransfer->origin" :destination="$store" :items="$supplyTransfer->suppliedItems" />
                @empty
                    <div class="card">
                        <div class="card-body text-center">
                            Sin transferencias
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <!--end::Main column-->
    </div>
    <div class="form_padding">
        <!--begin::Form-->
        <div class="form d-flex flex-column flex-lg-row">
        </div>
        <!--end::Aside column-->
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <!--end:::Tabs-->
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <x-btn-cancel-save :module="ModuleAliases::SUPPLY" routeCancel="{{ route('base.supply.index') }}" isShow="true" />
            </div>
        </div>
    </div>
</x-layouts.master-layout>
