<script setup>
import Master from "@layouts/Master.vue";
import { url } from "@src/utils.js";
import ConfirmationButton from "../../Components/ConfirmationButton.vue";

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    data: { type: Object, default: () => ({}) },
});
</script>

<template>
    <Master title="Colores" cardTitle="Colores">
        <div class="col-12">
            <div class="container">
                <div class="card-header border-0 pt-6 mb-3">
                    <div class="card-title">
                        <div class="align-items-center position-relative">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <a class="btn btn-primary" :href="url('color/create')">Agregar Color</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
                    <div class="col d-flex mb-4" v-for="(color, index) in data" :key="color.id">
                        <div class="card col-12 mb-xl-8 flex-grow-1">
                            <div :class="['card-body', color.status === 0 ? 'disabled-style' : '']">
                                <h2 class="card-title d-flex justify-content-between">
                                    <div :class="['d-flex align-items-center', color.status === 0 ? 'disabled-opacity' : '']">
                                        <!--begin::Thumbnail-->
                                        <span class="symbol symbol-50px">
                                            <span class="symbol-label" :style="color.texture_src ? { backgroundImage: `url(${color.texture_src})`, backgroundSize: 'cover' } : { backgroundColor: color.hexadecimal }">
                                            </span>
                                        </span>
                                        <!--end::Thumbnail-->
                                        <div class="ms-5">
                                            <!--begin::Title-->
                                            <b style="font-size: 18px;">{{ color.name }}</b>
                                            <!--end::Title-->
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex justify-content-center mb-3" v-if="color.status">
                                            <div class="col-sm-2 mx-2 hover-elevate-up">
                                                <a :href="url('color/' + color.id)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye icono" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="col-sm-2 mx-2 hover-elevate-up">
                                                <a :href="url('color/' + color.id + '/edit')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square icono" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="col-sm-2 mx-2 hover-elevate-up">
                                                <ConfirmationButton title="¿Estas seguro que deseas desactivar este Color?" text="El color será desactivado" :route="url('color/' + color.id)" buttonText="Desactivar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash3-fill icono" viewBox="0 0 16 16">
                                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />
                                                    </svg>
                                                </ConfirmationButton>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="col-sm-2 mx-2 hover-elevate-up enabled-style">
                                                <ConfirmationButton title="¿Estas seguro que deseas reactivar este Color?" text="El color será reactivado" :route="url('color/' + color.id + '/reactivate')" buttonText="Reactivar" method="post">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M8.43296491,7.17429118 L9.40782327,7.85689436 C9.49616631,7.91875282 9.56214077,8.00751728 9.5959027,8.10994332 C9.68235021,8.37220548 9.53982427,8.65489052 9.27756211,8.74133803 L5.89079566,9.85769242 C5.84469033,9.87288977 5.79661753,9.8812917 5.74809064,9.88263369 C5.4720538,9.8902674 5.24209339,9.67268366 5.23445968,9.39664682 L5.13610134,5.83998177 C5.13313425,5.73269078 5.16477113,5.62729274 5.22633424,5.53937151 C5.384723,5.31316892 5.69649589,5.25819495 5.92269848,5.4165837 L6.72910242,5.98123382 C8.16546398,4.72182424 10.0239806,4 12,4 C16.418278,4 20,7.581722 20,12 C20,16.418278 16.418278,20 12,20 C7.581722,20 4,16.418278 4,12 L6,12 C6,15.3137085 8.6862915,18 12,18 C15.3137085,18 18,15.3137085 18,12 C18,8.6862915 15.3137085,6 12,6 C10.6885336,6 9.44767246,6.42282109 8.43296491,7.17429118 Z" fill="#000000" fill-rule="nonzero" />
                                                        </g>
                                                    </svg>
                                                </ConfirmationButton>
                                            </div>
                                        </div>
                                    </div>
                                </h2>
                                <div :class="['col md-4-12', color.status === 0 ? 'disabled-opacity' : '']">
                                    <h6 class="fs-4 fw-semibold text-gray-500 mb-7 mt-4">Colores</h6>
                                    <div class="row row-cols-1 row-cols-md-2" v-if="color.children && color.children.length > 0">
                                        <div class="col mb-1" v-for="(child, index) in color.children" :key="child.id">
                                            <div class="d-flex align-items-left">
                                                <div class="col-md-12">
                                                    <p class="badge py-3 px-4 fs-7 badge-light-dark bg-gray-200 text-wrap w-100 d-flex align-items-center justify-content-center text-center">
                                                        <!--begin::Thumbnail-->
                                                        <span class="symbol symbol-20px">
                                                            <span class="symbol-label" :style="child.texture_src ? { backgroundImage: `url(${child.texture_src})`, backgroundSize: 'cover' } : { backgroundColor: child.hexadecimal }">
                                                            </span>
                                                        </span>
                                                        <!--end::Thumbnail-->
                                                        <!--begin::Title-->
                                                        <b class="mx-2 fs-5">{{ child.name }}</b>
                                                        <!--end::Title-->
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <p class="fw-bold w-100 text-center">No hay colores asignados a este tono</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Master>
</template>

<style>
.disabled-style {
    background-color: #f5f5f5;
    color: #a1a1a1;
    cursor: not-allowed;
    pointer-events: none;
}

.disabled-style .symbol-label {
    background-color: #dcdcdc;
}

.disabled-opacity {
    opacity: 0.5;
}

.enabled-style {
    background-color: transparent;
    color: #000000;
    cursor: pointer;
    pointer-events: auto;
}

.enabled-style .symbol-label {
    background-color: initial;
}
</style>
