<template>
    <textarea
        ref="input"
        v-model="text"
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>

<script lang="ts">

import { defineComponent } from "vue";

export default defineComponent({

    name: "SbTextarea",

    props: {
        modelValue: {
            default: "",
            type   : String,
        },
    },

    emits: ["update:modelValue"],

    data() {
        return {
            text: this.modelValue,
        };
    },

});

</script>
