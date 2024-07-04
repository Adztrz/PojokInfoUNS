<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { XMarkIcon, CheckCircleIcon, CameraIcon } from '@heroicons/vue/24/solid'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TabItem from '@/Pages/Profile/Partials/TabItem.vue';
import Edit from '@/Pages/Profile/Edit.vue';
import PostList from "@/Components/app/PostList.vue";
import PostCreate from '@/Components/app/PostCreate.vue';
import { usePage } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import TabPhotos from "@/Pages/Profile/TabPhotos.vue";

const imageForm = useForm({
    avatar: null,
    cover: null,
})

const showNotification = ref(true)
const coverImageSrc = ref('');
const avatarImageSrc = ref('');
const authUser = usePage().props.auth.user;

const isMyProfile = computed(() => authUser && authUser.id === props.user.id)

const props = defineProps({
    errors: Object,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    success: {
        type: String,
    },
    user: {
        type: Object
    },
    posts: Object,
    photos: Array
});

function onCoverChange(event) {
    imageForm.cover = event.target.files[0]
    if (imageForm.cover) {
        const reader = new FileReader()
        reader.onload = () => {
            coverImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imageForm.cover)
    }
}

function onAvatarChange(event) {
    imageForm.avatar = event.target.files[0]
    if (imageForm.avatar) {
        const reader = new FileReader()
        reader.onload = () => {
            avatarImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imageForm.avatar)
    }
}

function cancelCoverImage() {
    imageForm.cover = null;
    coverImageSrc.value = null;
}

function resetAvatarImage() {
    imageForm.avatar = null;
    avatarImageSrc.value = null;
}

function submitCoverImage() {
    imageForm.post(route('profile.updateImages'), {
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true;
            cancelCoverImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    });
}

function submitAvatarImage() {
    imageForm.post(route('profile.updateImages'), {
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true;
            resetAvatarImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    });
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-[768px] mx-auto h-full overflow-auto">
            <div v-show="showNotification && success"
                class="my-2 py-2 px-3 font-medium text-sm bg-emerald-500 text-white">
                {{ success }}
            </div>
            <div v-if="errors.cover" class="my-2 py-2 px-3 font-medium text-sm bg-red-500 text-white">
                {{ errors.cover }}
            </div>
            <div class="group relative bg-white">
                <img :src="coverImageSrc || user.cover_url || '/img/cover-1.jpeg'"
                    class="w-full h-[200px] object-cover" />
                <div class="absolute top-2 right-2">
                    <button v-if="!coverImageSrc"
                        class="bg-blue-50 hover:bg-blue-100 text-blue-800 py-1 px-2 text-xs flex items-center opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="size-3 mr-3">
                            <path d="M12 9a3.75 3.75 0 1 0 0 7.5A3.75 3.75 0 0 0 12 9Z" />
                            <path fill-rule="evenodd"
                                d="M9.344 3.071a49.52 49.52 0 0 1 5.312 0c.967.052 1.83.585 2.332 1.39l.821 1.317c.24.383.645.643 1.11.71.386.054.77.113 1.152.177 1.432.239 2.429 1.493 2.429 2.909V18a3 3 0 0 1-3 3h-15a3 3 0 0 1-3-3V9.574c0-1.416.997-2.67 2.429-2.909.382-.064.766-.123 1.151-.178a1.56 1.56 0 0 0 1.11-.71l.822-1.315a2.942 2.942 0 0 1 2.332-1.39ZM6.75 12.75a5.25 5.25 0 1 1 10.5 0 5.25 5.25 0 0 1-10.5 0Zm12-1.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                clip-rule="evenodd" />
                        </svg>

                        Update cover
                        <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                            @change="onCoverChange" />
                    </button>
                    <div v-else class="flex gap-2 bg-black p-2 opacity-0 group-hover:opacity-100">
                        <button @click="cancelCoverImage"
                            class="bg-blue-50 hover:bg-blue-100 text-blue-800 py-1 px-2 text-xs flex items-center">
                            <XMarkIcon class="size-3 mr-2" />

                            Cancel
                        </button>
                        <button @click="submitCoverImage"
                            class="bg-blue-800 hover:bg-blue-900 text-blue-100 py-1 px-2 text-xs flex items-center">
                            <CheckCircleIcon class="size-3 mr-2" />

                            Submit
                        </button>
                    </div>
                </div>
                <div class="flex">
                    <div
                        class="flex items-center justify-center relative group/avatar ml-[48px] -mt-[64px] w-[128px] h-[128px] rounded-full">
                        <img :src="avatarImageSrc || user.avatar_url || '/img/avatar-1.webp'"
                            class="w-full h-full object-cover rounded-full" />
                        <button v-if="!avatarImageSrc"
                            class="absolute left-0 top-0 bottom-0 right-0 bg-black/50 text-white rounded-full opacity-0 flex items-center justify-center group-hover/avatar:opacity-100">
                            <CameraIcon class="size-8" />

                            <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                @change="onAvatarChange" />
                        </button>
                        <div v-else class="absolute top-1 right-0 flex flex-col gap-2">
                            <button @click="resetAvatarImage"
                                class="size-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                <XMarkIcon class="size-3" />

                            </button>
                            <button @click="submitAvatarImage"
                                class="size-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                                <CheckCircleIcon class="size-5" />

                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center flex-1 p-3">
                        <h2 class="font-bold text-lg">
                            {{ user.name }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="border-t">
                <TabGroup>
                    <TabList class="flex bg-white">
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Post" :selected="selected" />
                        </Tab>
                        <!-- <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Follower" :selected="selected" />
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Followings" :selected="selected" />
                        </Tab> -->
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Photo" :selected="selected" />
                        </Tab>
                        <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
                            <TabItem text="My Profile" :selected="selected" />
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel>
                            <template v-if="posts">
                                <PostCreate/>
                                <PostList :posts="posts.data" class="flex-1"/>
                            </template>
                            <div v-else class="py-8 text-center dark:text-gray-100">
                                Permission denied
                            </div>
                        </TabPanel>
                        <!-- <TabPanel class="bg-white p-3 shadow">
                            Follower
                        </TabPanel>
                        <TabPanel class="bg-white p-3 shadow">
                            Following
                        </TabPanel> -->
                        <TabPanel class="bg-white p-3 shadow">
                            <TabPhotos :photos="photos" />
                        </TabPanel>
                        <TabPanel v-if="isMyProfile">
                            <Edit :must-verify-email="mustVerifyEmail" :status="status" />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
