@props([
    'suppliedItem' => null,
    'locked' => false,
])

<!--be gin::Input-->
@php
    use App\Enums\SupplyStatuses;
    use App\Enums\Auth\RoleAliases;
    use App\Enums\ItemIntegrities;

    $isSuperAdmin = auth()->user()?->hasAnyRole(RoleAliases::SUPER_ADMIN->value);
    $currentStatus = (int) $suppliedItem->status;
    $redistribution = SupplyStatuses::REDISTRIBUTION->value;
    $locked = $locked ?: (bool) ($suppliedItem->is_locked ?? false);
    $redirectSupplyId = $suppliedItem->redirected_to_supply_id ?? null;
    $readonlyClasses = $locked ? 'opacity-75' : '';
@endphp

<div class="card p-6 {{ $readonlyClasses }}">
    <div class="card-body p-0">
        @if ($locked)
            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                <i class="ki-duotone ki-information-2 fs-2 me-2"></i>
                <div>
                    Este artículo fue <strong>redistribuido</strong>.
                    @if ($redirectSupplyId)
                        Se redirigió a la orden
                        <a class="fw-bold"
                            href="{{ route('base.supply.show', $redirectSupplyId) }}">#{{ $redirectSupplyId }}</a>.
                    @else
                        Se redirigió a una nueva orden.
                    @endif
                </div>
            </div>
        @endif

        <div class="d-flex align-items-center overflow-auto mb-4">
            <div class="symbol symbol-success me-3">
                <img alt="Img" src="{{ $suppliedItem->item->product->firstImage->src_image }}"
                    style="object-fit: cover;" class="w-75px h-100px">
            </div>
            <div class="d-flex flex-column flex-grow-1 align-items-start">
                <h3 class="text-gray-900-50 fw-bold mb-2">{{ $suppliedItem->item->product->full_name }}</h3>
                <span class="text-muted fw-bold mb-2">
                    {{ $suppliedItem->item->serial_number }}
                </span>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge"
                        style="background-color: {{ $suppliedItem->item->variant->color->hexadecimal }};">Talla
                        {{ $suppliedItem->item->variant->size->full_name }}</span>
                    @if (!empty($suppliedItem->item->barcode))
                        <span class="fw-bold text-dark">Código: {{ $suppliedItem->item->barcode }}</span>
                    @else
                        <span class="fw-bold text-dark">Sin código</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap status-container">
            <select
                @if (!$locked) onchange="toggleChildVisibility(this.parentElement, '.error-fields', (this.value == {{ SupplyStatuses::ERROR->value }}))" @endif
                name="items[{{ $suppliedItem->id }}][status]" class="form-select form-control" data-control="select2"
                data-hide-search="true" data-placeholder="" @disabled($locked)>
                @foreach (SupplyStatuses::getAllNames() as $value => $name)
                    @php
                        $val = (int) $value;
                        $disableRedis =
                            !$isSuperAdmin && $val === $redistribution && $currentStatus !== $redistribution;
                    @endphp
                    <option value="{{ $val }}" @selected($val == old('items.' . $suppliedItem->id . '.status', $currentStatus)) @disabled($disableRedis)>
                        {{ $name }}
                    </option>
                @endforeach
            </select>

            <!--end::Input-->
            <!--begin::Input-->
                <!--begin::Input group-->
            <div class="error-fields col-12 mt-4 {{ $locked ? 'd-none' : 'd-none' }}">
                <div class="mb-2">
                    <!--begin::Label-->
                    <label class="required form-label">Integridad</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <div class="d-flex gap-3">
                        <select name="items[{{ $suppliedItem->id }}][integrity]" class="form-select form-control"
                            data-control="select2" data-hide-search="true" data-placeholder=""
                            @disabled($locked)>
                            <option selected hidden disabled>-- Elige una opción --</option>
                            @foreach (ItemIntegrities::getAllNames() as $value => $name)
                                <option value="{{ $value }}" @selected($value == old('items.' . $suppliedItem->id . '.integrity', $suppliedItem->integrity))>{{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-2">
                    <!--begin::Label-->
                    <label class="required form-label">Detalles</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <textarea class="form-control" placeholder="Detalles" name="items[{{ $suppliedItem->id }}][details]" rows="3"
                        @disabled($locked)>{{ old('items.' . $suppliedItem->id . '.details', is_array($suppliedItem->details) ? $suppliedItem->details['text'] ?? '' : $suppliedItem->details) }}</textarea>
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Input-->
        </div>
    </div>
</div>
