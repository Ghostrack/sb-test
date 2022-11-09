<template>
    <form @submit.prevent>
        <div>
            <input-label
                for="title"
                value="Title"
            />
            <text-input
                id="title"
                v-model="form.title"
                class="mt-1 block w-2/5"
                type="text"
                required
            />
            <input-error
                class="mt-2"
                :message="form.errors?.title"
            />
        </div>
        <div class="mt-2">
            <input-label
                for="content"
                value="Content"
            />
            <sb-textarea
                id="content"
                v-model="form.content"
                class="mt-1 block w-2/5 h-40"
                required
            />
            <input-error
                class="mt-2"
                :message="form.errors?.content"
            />
        </div>
    </form>
</template>

<script lang="ts">

import { defineComponent } from "vue";
import InputError from "../InputError.vue";
import InputLabel from "../InputLabel.vue";
import SbTextarea from "../SbTextarea.vue";
import TextInput from "../TextInput.vue";

import type { Form, Post } from "../../types";
import type { PropType } from "vue";

export default defineComponent({

    name: "PostForm",

    components: {
        InputError,
        InputLabel,
        SbTextarea,
        TextInput,
    },

    props: {
        modelValue: {
            default: () => ({
                content: "",
                title  : "",
            }),
            type: Object as PropType<Post>,
        },
    },

    emits: ["update:modelValue"],

    data() {
        return {
            form: {...this.modelValue} as Form & Post,
        };
    },

    watch: {
        form: {
            deep: true,
            handler() {
                this.$emit("update:modelValue", this.form);
            },
        },
        modelValue: {
            deep: true,
            handler() {
                this.form = {...this.modelValue};
            },
        },
    },

});

</script>
