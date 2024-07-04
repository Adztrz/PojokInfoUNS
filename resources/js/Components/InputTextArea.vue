<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        required: false,
    },
    placeholder: String,
    autoResize: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

function onInputChange($event) {
    emit('update:modelValue', $event.target.value);
    adjustHeight();
}

function adjustHeight() {
    if (props.autoResize) {
        input.value.style.height = 'auto';
        input.value.style.height = input.value.scrollHeight + 'px';
    }
}

onMounted(() => {
    adjustHeight();
})

</script>

<template>
    <textarea
        class="border-blue-300 dark:border-blue-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 rounded-md shadow-sm"
        :value="modelValue"
        @input="onInputChange"
        ref="input"
        :placeholder="placeholder"
    ></textarea>
</template>
