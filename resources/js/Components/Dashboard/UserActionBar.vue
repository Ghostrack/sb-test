<template>
    <div class="p-6 bg-white border-b border-gray-200">
        <div
            v-if="user"
            class="flex justify-between items-center"
        >
            <h2 class="text-xl font-bold align-middle">
                Your Frontpage
            </h2>
            <primary-button @click="openPostEditor()">
                New Post
            </primary-button>
        </div>
    </div>
</template>

<script lang="ts">

import { defineComponent } from "vue";
import { mapState } from "pinia";
import PrimaryButton from "../PrimaryButton.vue";
import { useUserStore } from "@/Stores/User";

export default defineComponent({

    name: "UserActionBar",

    components: {
        PrimaryButton,
    },

    computed: {
        ...mapState(useUserStore, ["user"]),
    },

    methods: {
        openPostEditor(): void {
            this.$inertia.get("/posts/create");
        },
    },

})

</script>
