<template>
    <component
        :is="isAuthenticated ? 'authenticated-layout' : 'guest-layout'"
        mode="dashboard"
    >
        <Head title="Dashboard" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                v-if="isAuthenticated"
                class="mt-5 bg-white overflow-hidden shadow-sm sm:rounded-lg"
            >
                <user-action-bar />
            </div>
            <post-list />
        </div>
    </component>
</template>

<script lang="ts">

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { defineComponent } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { mapActions } from "pinia";
import PostList from "@/Components/Posts/PostList.vue";
import { usePostStore } from "@/Stores/Post";
import UserActionBar from "@/Components/Dashboard/UserActionBar.vue";
import { useUserStore } from "@/Stores/User";

import type { Post, User } from "@/types";
import type { PropType } from "vue";

export default defineComponent({

    name: "UserDashboard",

    components: {
        AuthenticatedLayout,
        GuestLayout,
        Head,
        PostList,
        UserActionBar,
    },

    props: {
        favourites: {
            default : () => [],
            required: false,
            type    : Array as PropType<number[]>,
        },
        posts: {
            required: true,
            type    : Array as PropType<Post[]>,
        },
        user: {
            required: true,
            type    : Object as PropType<User>,
        },
    },

    computed: {
        isAuthenticated (): boolean {
            return Boolean(this.$page.props.auth.user);
        },
    },

    created(): void {
        this.setUser(this.user);
        this.setPosts(this.posts);
        this.setFavourites(this.favourites);
    },

    methods: {
        ...mapActions(usePostStore, [
            "setFavourites",
            "setPosts",
        ]),
        ...mapActions(useUserStore, ["setUser"]),
    },

});

</script>
