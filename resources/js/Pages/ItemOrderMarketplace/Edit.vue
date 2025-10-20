<script setup>
import { reactive, ref } from 'vue';
import Master from '@layouts/Master.vue';
import ItemSearch from '@/Pages/OrderMarketplace/Partials/Create/ItemSearch.vue';
import ItemEditionModal from '@/Pages/OrderMarketplace/Partials/Edit/ItemEditionModal.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    itemOrderMarketplace: { type: Object, required: true },
    itemDetails: { type: Object, required: true },
    order: { type: Object, required: true },
    itemResults: { type: Object, default: () => null },
    event: { type: Object, default: null },
    categories: { type: Object, required: true },
    designers: { type: Object, required: true },
    colors: { type: Object, required: true },
    characteristics: { type: Object, required: true },
});

// ------------------------
// Declarations
// ------------------------
const selectedItems = reactive({});
const itemEditionModal = ref(null);
const confirmItemSelection = (item) => {
    itemEditionModal.value.showWith(Object.assign(item, {
        order_detail: props.itemOrderMarketplace,
    }));
};
</script>

<template>
    <Master title="Cambiar artículo de Orden" :cardTitle="'Cambiar artículo ' + itemDetails.barcode  + ' de Orden #' + order.code">
        <div class="d-flex flex-column flex-xl-row">
            <!--begin::Content-->
            <div class="d-flex flex-row-fluid me-xl-9 mb-10 mb-xl-0">
                <!--begin::Pos food-->
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Tab Content-->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-flush mb-8">
                                <!--begin::Header-->
                                <div class="card-header px-7">
                                    <h5 class="card-title fw-bold text-gray-800 fs-2">Artículo actual</h5>
                                </div>
                                <div class="d-flex flex-column card-body text-center">
                                    <!--begin::Product img-->
                                        <img :src="itemDetails.src_image" class="col-12 rounded-3 mb-4">                                   
                                    <!--end::Product img-->
                                    <!--begin::Info-->
                                    <div class="mb-5">
                                        <!--begin::Title-->
                                        <div class="text-center">
                                            <div class="d-flex flex-column">
                                                <div class="fs-4 fw-bold text-gray-800">
                                                    {{ itemDetails.name }}
                                                </div>
                                                <div class="text-gray-700 fw-semibold d-block fs-5">
                                                    {{ itemDetails.barcode }}
                                                </div>
                                                <div class="text-gray-700 fw-semibold d-block fs-5">
                                                    {{ itemDetails.designer }}
                                                </div>
                                                <div class="d-flex justify-content-center color-info">
                                                    <div class="symbol symbol-20px symbol-circle mx-2 border border-primary">
                                                        <div class="symbol-label" :style="{ backgroundColor: itemDetails.color.hexadecimal }"></div>
                                                    </div>
                                                    <div class="color-text">{{ itemDetails.color.name }} {{itemDetails.size }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--begin::Item Search-->
                        <div class="col-md-9">
                            <ItemSearch :itemResults="itemResults" :selectedItems="selectedItems"
                                :onItemSelected="confirmItemSelection" :categories="categories" :designers="designers"
                                :colors="colors" :characteristics="characteristics" />
                        </div>
                        <!--end::Item Search-->
                    </div>
                    <!--end::Tab Content-->
                </div>
                <!--end: Card Body-->
                <!--end::Pos food-->
            </div>
            <!--end::Content-->
        </div>
    </Master>

    <ItemEditionModal ref="itemEditionModal" :order="order" :event="event" />
</template>