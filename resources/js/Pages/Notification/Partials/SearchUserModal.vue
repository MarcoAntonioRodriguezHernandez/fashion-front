<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    onUserSelected: { type: Function, required: true },
});

const searchForm = useForm({
    search: '',
});

// ------------------------
// Declarations
// ------------------------

const debounce = ref(null);
const usersOptions = ref([]);
const loading = ref(false);

const submitSearch = () => {
    if (searchForm.search.length < 3) {
        return;
    }

    searchForm.post(url('/notifications/user-search'), {
        onStart: () => {
            usersOptions.value = [];
            loading.value = true;
        },
        onSuccess: (page) => {
            usersOptions.value = page.props.usersResult;
        },
        onFinish: () => {
            loading.value = false;
        },
        preserveState: true,
        preserveScroll: true,
    });
}

const handleUserSelected = (user) => {
    props.onUserSelected(user);

    reset();
}

const reset = () => {
    usersOptions.value = [];
    searchForm.search = '';
}

const debounceAction = (action, timeout = 1000) => {
    clearTimeout(debounce.value);

    debounce.value = setTimeout(() => {
        action();
    }, timeout);
}
</script>

<template>
    <div class="modal fade" id="modal-notified-users" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--begin::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-10">
                    <!--begin::Heading-->
                    <div class="text-center mb-5">
                        <i class="ki-duotone ki-book" style="font-size: 4em;">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </div>
                    <div class="text-center mb-9">
                        <!--begin::Title-->
                        <h1 class="mb-3">Selección de empleados</h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div class="text-muted fw-semibold fs-5">Por favor busca mediante nombre o correo y selecciona
                            al empleado que deseas añadir a la lista de correos electrónicos.
                            <span class="link-primary fw-bold">Selecciona:</span>
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Heading-->

                    <!--begin::Users-->
                    <div class="d-flex align-items-center position-relative w-100 m-0 pb-3" autocomplete="off">
                        <!--begin::Icon-->
                        <i
                            class="ki-outline ki-magnifier fs-3 text-gray-500 position-absolute top-50 start-0 translate-middle-y ms-5"></i>
                        <!--end::Icon-->
                        <!--begin::Input-->
                        <div class="position-relative w-100">
                            <input v-model="searchForm.search" @input="debounceAction(submitSearch)" type="text"
                                class="form-control form-control-solid ps-5" placeholder="Buscar empleado">
                            <div class="position-absolute top-50 end-0 translate-middle-y px-2">
                                <div class="ki-outline spinner-border" role="status" v-if="loading">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </div>
                        </div>
                        <!--end::Input-->
                    </div>

                    <div class="mb-10">
                        <!--begin::List-->
                        <div class="mh-375px scroll-y me-n7 pe-7">
                            <!--begin::User-->
                            <div v-for="user in usersOptions"
                                class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                                <!--begin::Details-->
                                <div class="d-flex align-items-center w-100">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-35px symbol-circle">
                                        <img alt="Pic" :src="user.photo ?? url('/src/img/user-image.png')" />
                                    </div>
                                    <!--end::Avatar-->
                                    <!--begin::Details-->
                                    <div class="ms-6">
                                        <!--begin::Name-->
                                        <span
                                            class="d-flex align-items-center fs-5 fw-bold text-gray-900 text-hover-primary">{{
                                            user.full_name }}
                                            <span class="badge badge-light fs-8 fw-semibold ms-2">Staff</span></span>
                                        <!--end::Name-->
                                        <!--begin::Email-->
                                        <div class="fw-semibold text-muted">{{ user.email }}</div>
                                        <!--end::Email-->
                                    </div>
                                    <!--end::Details-->

                                    <!--begin::Action-->
                                    <button @click="handleUserSelected(user)" class="btn btn-icon btn-secondary ms-auto"
                                        type="button" data-bs-dismiss="modal">
                                        <i class="ki-duotone ki-right-square fs-2tx">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                    <!--end::Action-->
                                </div>
                                <!--end::Details-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::List-->
                    </div>
                    <!--end::Users-->
                    <!--begin::Actions-->
                    <div class="text-end">
                        <!--begin::Submit button-->
                        <button @click="reset()" type="button" class="btn btn-lg btn-primary fw-bold me-3 h-40px fs-7"
                            data-bs-dismiss="modal">Cancelar</button>
                        <!--end::Submit button-->
                    </div>
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
</template>
