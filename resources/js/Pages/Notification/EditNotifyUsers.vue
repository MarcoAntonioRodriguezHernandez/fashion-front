<script setup>
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Master from '@layouts/Master.vue';
import SearchUserModal from './Partials/SearchUserModal.vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    users: { type: Object, required: true },
    notificationTypes: { type: Object, required: true },
});

// ------------------------
// Declarations
// ------------------------

const selectedUser = ref(null);
const displayedUsers = computed(() => {
    if (selectedUser.value == null)
        return props.users;

    if (Object.keys(props.users).includes(selectedUser.value.id))
        return props.users;

    return {
        ...props.users,
        [selectedUser.value.id]: selectedUser.value
    };
});

const updateUserNotifications = () => {
    const user = selectedUser.value;

    router.post(url('/notifications/' + user.id + '/update'), {
        notifications: user.employee_detail.notifications_allowed_types
    }, {
        onSuccess: () => {
            selectedUser.value = null;
        }
    });
}
</script>

<template>
    <Master title="Correos de notificación" cardTitle="Correos de notificación">
        <div class="d-flex justify-content-end my-4 ">
            <div class="d-flex justify-content-end">
                <!--begin::Add user-->
                <a href="#" class="btn btn-primary fs-7 fw-bold" data-bs-toggle="modal"
                    data-bs-target="#modal-notified-users">Añadir usuarios a correos</a>
                <!--end::Add user-->
            </div>
        </div>

        <div class="card card-flush">
            <!--begin::Card body-->
            <div class="card-body pt-0 table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="max-w-125px">ID</th>
                            <th class="min-w-125px">Foto de perfil</th>
                            <th class="min-w-125px">Nombre</th>
                            <th class="min-w-150px">Email</th>
                            <th class="min-w-125px text-center" colspan="2">Notificaciones</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        <tr v-for="user in displayedUsers">
                            <td>{{ user.id }}</td>
                            <td class="min-w-125px d-flex align-items-left justify-content-left">
                                <a class="symbol symbol-50px">
                                    <img :src="user.photo ?? url('/src/img/user-image.png')" alt="user photo" />
                                </a>
                            </td>
                            <td class="min-w-100px">
                                <a class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    :href="url('/user/' + user.id)">{{ user.full_name }}</a>
                            </td>
                            <td>
                                <span class="fw-bold">{{ user.email }}</span>
                            </td>
                            <td class="d-flex flex-wrap justify-content-around border-0">
                                <div v-for="name, value in notificationTypes" class="form-check align-self-center m-1">
                                    <input v-model="user.employee_detail.notifications_allowed_types"
                                        class="form-check-input" type="checkbox" :value="value"
                                        :id="user.id + '-' + value" :disabled="selectedUser?.id != user.id">
                                    <label class="form-check-label" :for="user.id + '-' + value">{{ name }}</label>
                                </div>
                            </td>
                            <td class="p-0 text-center">
                                <!--begin::Save action-->
                                <button v-if="selectedUser?.id == user.id" @click="updateUserNotifications"
                                    class="btn btn-icon btn-sm btn-success" type="button" :key="'save-' + user.id">
                                    <i class="ki-duotone ki-check-square fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                                <!--end::Save action-->

                                <!--begin::Edit action-->
                                <button v-if="selectedUser?.id != user.id" @click="selectedUser = user"
                                    class="btn btn-icon btn-sm btn-warning" type="button" :key="'edit-' + user.id">
                                    <i class="ki-duotone ki-notepad-edit fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                                <!--end::Edit action-->
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
    </Master>

    <SearchUserModal :onUserSelected="(v) => selectedUser = v" />
</template>
