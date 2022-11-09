<template>
    <button
        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
        :type="type"
    >
        <slot />
    </button>
</template>

<script lang="ts">

import { defineComponent } from "vue";

import type { PropType } from "vue";

export default defineComponent({

    props: {
        type: {
            default: "submit",
            type   : String as PropType<"button" | "reset" | "submit">,
        },
    },

});

</script>
