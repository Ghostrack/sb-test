<template>
    <authenticated-layout>
        <Head title="New Post" />
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isEditing ? 'Edit post' : 'Create new post' }}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <primary-button
                                class="bg-red-500"
                                @click.prevent="goBack()"
                            >
                                Back
                            </primary-button>
                            <primary-button
                                class="bg-green-600"
                                @click.prevent="save()"
                            >
                                Save
                            </primary-button>
                        </div>
                        <post-form
                            v-model="post"
                            class="mt-5"
                        />
                    </div>
                </div>
            </div>
        </div>
    </authenticated-layout>
</template>

<script lang="ts">

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { defineComponent } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { mapStores } from "pinia";
import PostForm from "@/Components/Posts/PostForm.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useUserStore } from "@/Stores/User";

import type { Form, Post } from "../../types";
import type { PropType } from "vue";

export default defineComponent({

    name: "PostEditor",

    components: {
        AuthenticatedLayout,
        Head,
        PostForm,
        PrimaryButton,
    },

    props: {
        initialPostData: {
            default: () => ({}),
            type   : Object as PropType<Post>,
        },
    },

    data() {
        return {
            post: {...this.initialPostData} as Form & Post,
        };
    },

    computed: {

        ...mapStores(useUserStore),

        isEditing(): boolean {
            return this.post.id !== undefined && this.post.id > 0;
        },

    },

    methods: {

        goBack(): void {
            window.history.back();
        },

        save(): void {
            const postData: Record<string, number | string> = {
                content: this.post.content,
                title  : this.post.title,
            };

            const requestOptions = {
                onError: (errors: Record<string, string>): void => {
                    this.post.errors = errors;
                },
            };

            if (this.isEditing) {
                Inertia.put(`/posts/${this.post.id ?? ""}`, postData, requestOptions);
            } else {
                Inertia.post("/posts", postData, requestOptions);
            }
        },

    },

});

</script>
