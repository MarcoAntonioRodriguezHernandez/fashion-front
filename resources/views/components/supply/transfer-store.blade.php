@props([
    'origin' => null,
    'destination' => null,
    'items' => [],
    'url' => null,
])

<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="col-12 mt-4">
            <div class="col-12 my-4">
                <div class="col-12 row">
                    <h3 class="col">Distribución {{ $origin->name }} -> {{ $destination->name }}</h3>
                    <div class="col-2 mb-1">
                        <span class="col-12 d-flex justify-content-end">
                            <i class="ki-duotone ki-information-4 text-info" style="font-size: 2em;" data-bs-toggle="tooltip" data-bs-placement="top" title="Seleccione para más información">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </div>
                </div>
                <span class="text-gray-500 mt-1 fw-semibold fs-6">
                    Seleccione alguna distribución para ver más información
                </span>
            </div>
            <hr class="my-2">
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Main column-->
    <div class="card-body">
        <div class="col-12 mb-2">
            <div class="row row-cols-1 row-cols-lg-2">
                @forelse ($items as $suppliedItem)
                    @php
                        $img =
                            (string) ($suppliedItem->item->product->firstImage->src_image ??
                                asset('media/misc/image.png'));
                        $hex = (string) ($suppliedItem->item->variant->color->hexadecimal ?? '#999');
                        $rawDetails = $suppliedItem->details;
                        $detailsText = is_array($rawDetails) ? $rawDetails['text'] ?? null : $rawDetails;
                        if (is_array($detailsText)) {
                            $detailsText = json_encode($detailsText, JSON_UNESCAPED_UNICODE);
                        }

                        $isLocked = (bool) ($suppliedItem->is_locked ?? false);
                        $toSupplyId = $suppliedItem->redirected_to_supply_id ?? null;
                    @endphp

                    <div class="col p-3">
                        <div class="card px-4">
                            <div class="d-flex align-items-center overflow-auto">
                                <div class="symbol symbol-success me-3 mb-2">
                                    <img alt="Img" src="{{ $img }}" style="object-fit: cover;"
                                        class="w-75px h-100px">
                                </div>
                                <div class="d-flex flex-column flex-grow-1">
                                     @if ($isLocked)
                                        <div class="alert alert-warning d-flex align-items-center my-2" role="alert">
                                            <i class="ki-duotone ki-information-2 fs-2 me-2"></i>
                                            <div>
                                                Este artículo fue <strong>redistribuido</strong>.
                                                @if ($toSupplyId)
                                                    Revisa la orden
                                                    <a class="fw-bold"
                                                        href="{{ route('base.supply.show', $toSupplyId) }}">#{{ $toSupplyId }}</a>.
                                                @else
                                                    Revisa la nueva orden de redistribución.
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <a href="{{ route('base.supply_transfer.edit.view', $suppliedItem->supply_transfer_id) }}" class="text-gray-900-50 fw-bold mb-2 stretched-link">{{ $suppliedItem->item->product->full_name }}</a>
                                    <div class="d-flex flex-row justify-content-between flex-wrap mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <x-common.common-badge backgroundColor="{{ $hex }}">
                                                {{ $suppliedItem->item->variant->size->full_name }}
                                            </x-common.common-badge>
                                            @if (!empty($suppliedItem->item->barcode))
                                                <span class="fw-bold text-dark">Código: {{ $suppliedItem->item->barcode }}</span>
                                            @else
                                                <span class="fw-bold text-dark">Sin código</span>
                                            @endif
                                        </div>
                                        <span class="badge badge-{{ $suppliedItem->statusColor }} cursor-default">
                                            {{ $suppliedItem->statusName }}
                                            @if ($suppliedItem->integrity)
                                                : {{ $suppliedItem->integrity_name }}
                                            @endif
                                        </span>
                                    </div>

                                    <div>{{ $detailsText ?? 'Ninguna Nota' }}</div>

                                    <div class="d-flex justify-content-between">
                                        <div></div>
                                        <div class="text-muted">Receptor: {{ $suppliedItem->supplyTransfer->recipient?->full_name ?? 'No asignado' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center">
                            Sin artículos en esta transferencia
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!--end::Main column-->
</div>
