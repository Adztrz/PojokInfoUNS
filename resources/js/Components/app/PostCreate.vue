<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import PostModal from '@/Components/app/PostModal.vue'

const authUser = usePage().props.auth.user;

const showModal = ref(false);
const newPost = ref({
    id: null,
    body: '',
    user: authUser
})

defineProps({
    group: {
        type: Object,
        default: null
    }
})

function showCreatePostModal() {
    showModal.value = true
}

</script>

<template>
    <div class="p-4 bg-white rounded-lg border mb-3">
        <div @click="showCreatePostModal"
            class="py-2 px-3 border-2 border-blue-300 text-gray-600 rounded-md mb-3 w-full">
            Click to create post
        </div>
        <PostModal :post="newPost" :group="group" v-model="showModal" />
    </div>
</template>

<style scoped></style>
