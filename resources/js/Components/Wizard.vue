<script setup>
import { reactive, computed, onUpdated } from 'vue';

// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    stepsInfo: { type: Array, required: true },
    showNavBar: { type: Boolean, default: true },
});

onUpdated(() => {
    props.stepsInfo[context.currentIndex].onShow();
});

// ------------------------
// Declarations
// ------------------------

const context = reactive({
    currentIndex: 0,
});

const currentStep = computed(() => {
    return props.stepsInfo[context.currentIndex];
});

const setStep = (newStep) => {
    newStep = Math.min(props.stepsInfo.length - 1, Math.max(0, newStep));

    context.currentIndex = newStep;
}

const stepperNav = {
    setStep: (newStep) => {
        setStep(newStep);
    },
    prevStep: () => {
        setStep(context.currentIndex - 1);
    },
    nextStep: () => {
        setStep(context.currentIndex + 1);
    },
};

// ------------------------
// Initialize Component
// ------------------------

for (const stepInfo of props.stepsInfo) { // Set default values for each step
    stepInfo.showStep ??= () => true;
    stepInfo.enabled ??= () => true;
    stepInfo.onShow ??= () => {};
    stepInfo.actions = stepInfo.actions !== false && {
        prev: stepInfo.actions?.prev ?? ((stepper) => stepper.prevStep()),
        next: stepInfo.actions?.next ?? ((stepper) => stepper.nextStep()),
    };
}
</script>

<template>
    <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid gap-10">
        <!--begin::Aside-->
        <div v-if="showNavBar" class="stepper-nav min-w-250px">
            <!--begin::Step-->
            <div v-for="stepInfo, index in stepsInfo" :class="{ 'stepper-item': true, 'completed': context.currentIndex > index, 'current': context.currentIndex == index }">
                <template v-if="stepInfo.showStep()">
                    <!--begin::Wrapper-->
                    <div class="stepper-wrapper">
                        <!--begin::Icon-->
                        <div class="stepper-icon w-40px h-40px">
                            <i class="ki-duotone ki-check fs-2 stepper-check"></i>
                            <span class="stepper-number">{{ index + 1 }}</span>
                        </div>
                        <!--end::Icon-->

                        <!--begin::Label-->
                        <div class="stepper-label">
                            <h3 class="stepper-title">
                                {{ stepInfo.title }}
                            </h3>

                            <div class="stepper-desc fw-semibold">
                                {{ stepInfo.description }}
                            </div>
                        </div>
                        <!--end::Label-->
                    </div>
                    <!--end::Wrapper-->
                </template>

                <!--begin::Line-->
                <div v-if="index != stepsInfo.length - 1" class="stepper-line h-40px"></div>
                <!--end::Line-->
            </div>
            <!--end::Step-->
        </div>
        <!--begin::Aside-->

        <!--begin::Content-->
        <div class="flex-grow-1 p-6">
            <!--begin::Current Step-->
            <div class="w-100">
                <slot :name="currentStep.name" :info="currentStep" :stepperNav="stepperNav"></slot>
            </div>
            <!--end::Current Step-->

            <!--begin::Actions-->
            <div v-if="currentStep.actions" class="d-flex flex-stack mt-6">
                <!--begin::Wrapper-->
                <div v-if="currentStep.actions.prev" class="mr-2">
                    <button v-if="context.currentIndex > 0" @click="currentStep.actions.prev(stepperNav)" type="button" class="btn btn-lg btn-light-primary me-3">
                        <i class="ki-duotone ki-arrow-left fs-4 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Anterior
                    </button>
                </div>
                <!--end::Wrapper-->

                <!--begin::Wrapper-->
                <div v-if="currentStep.actions.next">
                    <button @click="currentStep.actions.next(stepperNav)" :disabled="!currentStep.enabled()" type="button" class="btn btn-lg btn-primary">
                        Siguiente
                        <i class="ki-duotone ki-arrow-right fs-4 ms-1 me-0">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Content-->
    </div>
</template>
