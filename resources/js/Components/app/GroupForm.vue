<script setup>
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputTextArea from '@/Components/InputTextArea.vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

defineProps({
    form: Object
})

const editor = ClassicEditor;
const editorConfig = {
    toolbar: ['bold', 'italic', '|', 'bulletedList', 'numberedList', '|', 'heading', '|', 'outdent', 'indent', '|', 'link', '|', 'blockQuote'],
}

</script>

<template>
    <div class="mb-3">
        <label>Group name</label>
        <TextInput type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
    </div>

    <div class="mb-3">
        <label>
            <Checkbox name="remember" v-model:checked="form.auto_approval" />
            Enable auto approval
        </label>
    </div>

    <div class="mb-3">
        <label>About group</label>
        <ckeditor :editor="editor" v-model="form.about" :config="editorConfig"></ckeditor>
    </div>
</template>

<style scoped></style>
