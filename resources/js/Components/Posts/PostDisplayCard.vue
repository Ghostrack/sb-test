<template>
    <div class="p-6 bg-white border-b border-gray-200 flex flex-col shadow-sm sm:rounded-lg">
        <div class="flex place-content-between">
            <h2 class="text-2xl font-bold">
                {{ post.title }}
            </h2>
            <div class="flex items-center">
                <post-favourite-controls
                    :post="post"
                    class="ml-2"
                />
                <post-visibility-controls
                    v-if="isAdmin || isAuthor"
                    :post="post"
                    class="ml-2"
                />
            </div>
        </div>
        <span class="mt-3 pb-3 border-b-2">
            {{ post.content }}
        </span>
        <div class="mt-3">
            Posted: {{ post.created_at }}
        </div>
        <div v-if="hasBeenEdited">
            Last Edited: {{ post.updated_at }}
        </div>
        <div>
            Author: {{ post.user?.name }}
        </div>
        <div
            v-if="isAuthor"
            class="flex justify-end"
        >
            <primary-button
                class="bg-yellow-400"
                @click="editPost()"
            >
                Edit
            </primary-button>
            <primary-button
                class="bg-red-500 ml-3"
                @click="showConfirmationAlert = true"
            >
                Delete
            </primary-button>
        </div>
        <sb-alert
            v-if="showConfirmationAlert"
            title="Confirm"
            content="Are you sure you want to delete this post? This action can't be undone and the post will be permanently deleted"
            @cancel="showConfirmationAlert = false"
            @confirm="deletePost()"
        />
    </div>
</template>

<script lang="ts">

import { mapActions, mapState } from "pinia";

import axios from "axios";
import { defineComponent } from "vue";
import PostFavouriteControls from "./PostFavouriteControls.vue";
import PostVisibilityControls from "./PostVisibilityControls.vue";
import PrimaryButton from "../PrimaryButton.vue";
import SbAlert from "@/Components/SbAlert.vue";
import { usePostStore } from "@/Stores/Post";
import { useUserStore } from "@/Stores/User";

import type { Post } from "../../types";
import type { PropType } from "vue";

export default defineComponent({

    name: "PostDisplayCard",

    components: {
        PostFavouriteControls,
        PostVisibilityControls,
        PrimaryButton,
        SbAlert,
    },

    props: {
        post: {
            required: true,
            type    : Object as PropType<Post>,
        },
    },

    data() {
        return {
            showConfirmationAlert: false,
        };
    },

    computed: {

        ...mapState(useUserStore, [
            "isAdmin",
            "user",
        ]),

        hasBeenEdited(): boolean {
            return this.post.created_at !== this.post.updated_at;
        },

        isAuthor(): boolean {
            return this.user?.id === this.post.user?.id;
        },

    },

    methods: {

        ...mapActions(usePostStore, ["removePost"]),

        async deletePost(): Promise<void> {
            if (this.post.id !== undefined) {
                await axios.delete(`/posts/${this.post.id}`);

                this.removePost(this.post.id);
            }
        },

        editPost(): void {
            if (this.post.id !== undefined) {
                this.$inertia.get(`/posts/${this.post.id}/edit`);
            }
        },

    },

});

</script>
