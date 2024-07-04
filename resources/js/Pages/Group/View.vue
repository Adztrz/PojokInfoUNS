<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { XMarkIcon, CheckCircleIcon, CameraIcon } from '@heroicons/vue/24/solid'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TabItem from '@/Pages/Profile/Partials/TabItem.vue';
import UserListItem from '@/Components/app/UserListItem.vue';
import TextInput from '@/Components/TextInput.vue';
import GroupForm from '@/Components/app/GroupForm.vue'
import TabPhotos from "@/Pages/Profile/TabPhotos.vue";
import Edit from '@/Pages/Profile/Edit.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InviteUserModal from '@/Pages/Group/InviteUserModal.vue';
import PostList from '@/Components/app/PostList.vue';
import PostCreate from '@/Components/app/PostCreate.vue';
import { usePage } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

const imageForm = useForm({
    thumbnail: null,
    cover: null,
})

const showNotification = ref(true)
const coverImageSrc = ref('');
const thumbnailImageSrc = ref('');
const showInviteUserModal = ref(false);
const searchKeyword = ref('');

const authUser = usePage().props.auth.user;

const isCurrentUserAdmin = computed(() => props.group.role_group === 'admin')
const isJoinedToGroup = computed(() => props.group.role_group && props.group.status === 'approved')

const props = defineProps({
    errors: Object,
    success: {
        type: String,
    },
    group: {
        type: Object
    },
    posts: Object,
    users: Array,
    requests: Array,
    photos: Array,
});

const aboutForm = useForm({
    name: usePage().props.group.name,
    auto_approval: !!parseInt(usePage().props.group.auto_approval),
    about: usePage().props.group.about
})

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

function onThumbnailChange(event) {
    imageForm.thumbnail = event.target.files[0]
    if (imageForm.thumbnail) {
        const reader = new FileReader()
        reader.onload = () => {
            thumbnailImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imageForm.thumbnail)
    }
}

function resetCoverImage() {
    imageForm.cover = null;
    coverImageSrc.value = null;
}

function resetThumbnailImage() {
    imageForm.thumbnail = null;
    thumbnailImageSrc.value = null;
}

function submitCoverImage() {
    imageForm.post(route('group.updateImages', props.group.slug), {
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true;
            resetCoverImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    });
}

function submitThumbnailImage() {
    imageForm.post(route('group.updateImages', props.group.slug), {
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true;
            resetThumbnailImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    });
}

function joinToGroup() {
    const form = useForm({})

    form.post(route('group.join', props.group.slug), {
        preserveScroll: true
    })
}

function approveUser(user) {
    const form = useForm({
        user_id: user.id,
        action: 'approve'
    })

    form.post(route('group.approveRequest', props.group.slug), {
        preserveScroll: true
    })
}

function rejectUser(user) {
    const form = useForm({
        user_id: user.id,
        action: 'reject'
    })

    form.post(route('group.approveRequest', props.group.slug), {
        preserveScroll: true
    })
}

function deleteUser(user) {
    if (!window.confirm('Remove user "${user.name}" from group?')) {
        return false;
    }

    const form = useForm({
        user_id: user.id,
    })

    form.delete(route('group.removeUser', props.group.slug), {
        preserveScroll: true
    })
}

function onRoleChange(user, role_group) {
    console.log(user, role_group)
    const form = useForm({
        user_id: user.id,
        role_group
    })

    form.post(route('group.changeRole', props.group.slug), {
        preserveScroll: true
    })
}

function updateGroup() {
    aboutForm.put(route('group.update', props.group.slug), {
        preserveScroll: true
    })
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-[768px] mx-auto h-full overflow-auto">
            <div class="px-4">
                <div v-show="showNotification && success"
                    class="my-2 py-2 px-3 font-medium text-sm bg-emerald-500 text-white">
                    {{ success }}
                </div>

                <div v-if="errors.cover" class="my-2 py-2 px-3 font-medium text-sm bg-red-500 text-white">
                    {{ errors.cover }}
                </div>

                <div class="group relative bg-white">
                    <img :src="coverImageSrc || group.cover_url || '/img/cover-1.jpeg'"
                        class="w-full h-[200px] object-cover" />
                    <div v-if="isCurrentUserAdmin" class="absolute top-2 right-2">
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
                            <button @click="resetCoverImage"
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
                            class="flex items-center justify-center relative group/thumbnail ml-[48px] -mt-[64px] w-[128px] h-[128px] rounded-full">
                            <img :src="thumbnailImageSrc || group.thumbnail_url || '/img/avatar-1.webp'"
                                class="w-full h-full object-cover rounded-full" />
                            <button v-if="isCurrentUserAdmin && !thumbnailImageSrc"
                                class="absolute left-0 top-0 bottom-0 right-0 bg-black/50 text-white rounded-full opacity-0 flex items-center justify-center group-hover/thumbnail:opacity-100">
                                <CameraIcon class="size-8" />

                                <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                    @change="onThumbnailChange" />
                            </button>
                            <div v-else-if="isCurrentUserAdmin" class="absolute top-1 right-0 flex flex-col gap-2">
                                <button @click="resetThumbnailImage"
                                    class="size-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                    <XMarkIcon class="size-3" />

                                </button>
                                <button @click="submitThumbnailImage"
                                    class="size-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                                    <CheckCircleIcon class="size-5" />

                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between items-center flex-1 p-3">
                            <h2 class="font-bold text-lg">
                                {{ group.name }}
                            </h2>

                            <PrimaryButton v-if="!authUser" :href="route('login')">
                                Login to join group
                            </PrimaryButton>

                            <PrimaryButton @click="showInviteUserModal = true" v-if="isCurrentUserAdmin">
                                Invite user
                            </PrimaryButton>

                            <PrimaryButton @click="joinToGroup"
                                v-if="authUser && !group.role_group && group.auto_approval">
                                Join group
                            </PrimaryButton>

                            <PrimaryButton @click="joinToGroup"
                                v-if="authUser && !group.role_group && !group.auto_approval">
                                Request join
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t m-4 pmt-0">
                <TabGroup>
                    <TabList class="flex bg-white">
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Post" :selected="selected" />
                        </Tab>
                        <Tab v-if="isJoinedToGroup" v-slot="{ selected }" as="template">
                            <TabItem text="Users" :selected="selected" />
                        </Tab>
                        <Tab v-if="isCurrentUserAdmin" v-slot="{ selected }" as="template">
                            <TabItem text="Pending Requests" :selected="selected" />
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Photo" :selected="selected" />
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="About" :selected="selected" />
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel>
                            <template v-if="posts">
                                <PostCreate :group="group" />
                                <PostList v-if="posts.data.length" :posts="posts.data" class="flex-1" />
                                <div v-else class="py-8 text-center">
                                    No posts
                                </div>
                            </template>
                            <div v-else class="py-8  text-center">
                                Permission denied
                            </div>
                        </TabPanel>
                        <TabPanel v-if="isJoinedToGroup">
                            <div class="mb-3">
                                <TextInput :model-value="searchKeyword" placeholder="Search groups" class="w-full" />
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <UserListItem v-for="user of users" :user="user" :key="user.id"
                                    :show-role-dropdown="isCurrentUserAdmin"
                                    :disable-role-dropdown="group.user_id === user.id" class="shadow rounded-lg"
                                    @role-change="onRoleChange" @delete="deleteUser" />
                            </div>
                        </TabPanel>
                        <TabPanel v-if="isCurrentUserAdmin" class="">
                            <div v-if="requests.length" class="grid grid-cols-2 gap-3">
                                <UserListItem v-for="user of requests" :user="user" :key="user.id" :for-approve="true"
                                    class="shadow rounded-lg" @approve="approveUser" @reject="rejectUser" />
                            </div>

                            <div class="py-8 text-center">
                                No pending requests.
                            </div>
                        </TabPanel>
                        <TabPanel class="bg-white p-3 shadow">
                            <TabPhotos :photos="photos" />
                        </TabPanel>
                        <TabPanel class="bg-white p-3 shadow">
                            <template v-if="isCurrentUserAdmin">
                                <GroupForm :form="aboutForm" />
                                <PrimaryButton @click="updateGroup">Submit</PrimaryButton>
                            </template>
                            <div v-else class="ck-content-output" v-html="group.about">
                            </div>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
    <InviteUserModal v-model="showInviteUserModal" />
</template>

<style scoped></style>
