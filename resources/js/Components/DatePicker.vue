<script setup>
import { computed, reactive, toRaw } from 'vue';
import { dateDiffInDays, checkDateBetween, currentLocalDate, normalizedDate } from '../src/utils';

import '@/../sass/components/_date-picker.scss';

// ------------------------
// Component Attributes
// ------------------------

const selectedDatesValue = defineModel();

const emits = defineEmits(['startSelection', 'finishSelection']);

const props = defineProps({
    single: { type: Boolean, default: false },
    disabledRanges: { type: Array, default: () => [] },
    minDate: { type: Date, default: null },
    maxDate: { type: Date, default: null },
    locale: { type: String, default: 'es-MX' },
});

const selectedDates = reactive({
    startDate: selectedDatesValue.value?.startDate,
    endDate: selectedDatesValue.value?.endDate,
});

// ------------------------
// Declarations
// ------------------------

const context = reactive({
    month: selectedDates.startDate?.getMonth() ?? currentLocalDate().getMonth(),
    year: selectedDates.startDate?.getFullYear() ?? currentLocalDate().getFullYear(),
});

const disabledRanges = computed(() => {
    const ranges = props.disabledRanges.map(range => ({
        startDate: normalizedDate(range.startDate),
        endDate: normalizedDate(range.endDate),
    }));

    if (props.minDate) {
        ranges.push({
            startDate: normalizedDate(Date.MIN_VALUE),
            endDate: normalizedDate(props.minDate),
        });
    }

    if (props.maxDate) {
        ranges.push({
            startDate: normalizedDate(props.maxDate),
            endDate: normalizedDate(Date.MAX_VALUE),
        });
    }

    return ranges;
});

const displayedDates = computed(() => {
    const firstDate = new Date(context.year, context.month, 1);
    const lastDate = new Date(context.year, context.month + 1, 0);

    while (firstDate.getDay() !== 0) {
        firstDate.setDate(firstDate.getDate() - 1);
    }

    while (lastDate.getDay() !== 0) {
        lastDate.setDate(lastDate.getDate() + 1);
    }

    return Array.from({ length: dateDiffInDays(firstDate, lastDate) }, (_, i) => {
        const date = new Date(firstDate.getTime());
        date.setDate(date.getDate() + i);

        return {
            index: i,
            date,
            active: date.getTime() == selectedDates.startDate?.getTime() || checkDateBetween(date, selectedDates.startDate, selectedDates.endDate),
            disabled: disabledRanges.value.some(range => checkDateBetween(date, range.startDate, range.endDate)),
            startDate: date.getTime() == selectedDates.startDate?.getTime(),
            endDate: date.getTime() == selectedDates.endDate?.getTime() || (date.getTime() == selectedDates.startDate?.getTime() && selectedDates.endDate == null),
        };
    });
});

const onDateSelected = (index) => {
    if (!selectedDates.startDate) {
        startSelection(index);
    } else if (!selectedDates.endDate) {
        finishSelection(index);
    } else {
        startSelection(index, true);
    }

    if (props.single) {
        finishSelection(index);
    }
}

const startSelection = (index, reset = false) => {
    const startObject = displayedDates.value[index];
    selectedDates.startDate = startObject.date;

    if (disabledRanges.value.some(range => checkDateBetween(selectedDates.startDate, range.startDate, range.endDate))) { // Check if the range is disabled
        selectedDates.startDate = null;
        selectedDates.endDate = null;
    }

    if (reset)
        selectedDates.endDate = null;

    updateModelValue(null, null);

    emits('startSelection', toRaw(selectedDates), toRaw(startObject));
}

const finishSelection = (index) => {
    while (index >= displayedDates.value.length) {
        index -= displayedDates.value.length;

        if (displayedDates.value.at(-1).date.getMonth() != context.month)
            index += 7;

        deltaMonth(1);
    }

    const endObject = displayedDates.value[index];

    selectedDates.endDate = endObject.date;

    if (selectedDates.startDate > selectedDates.endDate) {
        const tmp = selectedDates.startDate;
        selectedDates.startDate = selectedDates.endDate;
        selectedDates.endDate = tmp;
    }

    if (disabledRanges.value.some(range => checkDateBetween(range.startDate, selectedDates.startDate, selectedDates.endDate))) // Check if the range is disabled
        return startSelection(index, true);

    updateModelValue(selectedDates.startDate, selectedDates.endDate);

    emits('finishSelection', toRaw(selectedDates), toRaw(endObject));
}

const clearSelection = () => {
    selectedDates.startDate = null;
    selectedDates.endDate = null;

    emits('finishSelection', toRaw(selectedDates), null);

    updateModelValue(null, null);
}

const updateModelValue = (startDate, endDate) => {
    selectedDatesValue.value = Object.assign({}, { startDate, endDate });
}

const deltaMonth = (delta) => {
    context.month += delta;

    if (context.month < 0) {
        context.month = 11;
        context.year--;
    } else if (context.month > 11) {
        context.month = 0;
        context.year++;
    }
}

const formatSelectedDate = (date) => {
    return date?.toLocaleDateString(props.locale, { year: 'numeric', month: '2-digit', day: '2-digit' });
}

defineExpose({
    startSelection,
    finishSelection,
    clearSelection,
});
</script>

<template>
    <div class="calendar" style="max-width: 300px;">
        <div class="col-12 shadow-sm rounded mb-4 py-2">
            <span>{{ formatSelectedDate(selectedDates.startDate) }}</span>
            <span v-if="!props.single || selectedDates.startDate == null"> - </span>
            <span v-if="!props.single">{{ formatSelectedDate(selectedDates.endDate) }}</span>
        </div>

        <div class="col-12 shadow rounded p-3">
            <div class="d-flex flex-row align-items-center mt-0 m-4">
                <div class="flex-shrink-1">
                    <button @click="deltaMonth(-1)" class="btn prev" type="button">
                        <span></span>
                    </button>
                </div>
                <div class="flex-grow-1">
                    {{ new Date(context.year, context.month + 1, 0).toLocaleString(locale, { month: 'short', year: 'numeric' }) }}
                </div>
                <div class="flex-shrink-1">
                    <button @click="deltaMonth(1)" class="btn next" type="button">
                        <span></span>
                    </button>
                </div>
            </div>

            <div class="row mx-0 mb-2">
                <div v-for="day in 7" class="button-wrapper">
                    <label class="col-12 ">{{ displayedDates[day + 6].date.toLocaleDateString(locale, { weekday: 'short' }) }}</label>
                </div>
            </div>

            <div class="row mx-0">
                <div v-for="info in displayedDates" class="button-wrapper">
                    <button @click="onDateSelected(info.index)" :class="{
                        'col-12 text-nowrap btn fw-semibold rounded-0 px-0 py-2': true,
                        'off': info.date.getMonth() != context.month,
                        'active-range': info.active,
                        'active-edge': info.startDate || info.endDate,
                        'initial-edge': info.startDate,
                        'final-edge': info.endDate,
                    }" :disabled="info.disabled" type="button">
                        {{ info.date.getDate() }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
