<template>
    <div :class="{'pointer-events-none': !isAdmin}">
        <icon-hidden
            v-if="post.is_hidden"
            class="cursor-pointer w-8 fill-red-600"
            @click="updateIsHidden(false)"
        />
        <icon-visible
            v-else
            class="cursor-pointer w-8 fill-green-600"
            @click="updateIsHidden(true)"
        />
    </div>
</template>

<script lang="ts">

import { mapActions, mapState } from "pinia";

import axios from "axios";
import { defineComponent } from "vue";
import IconHidden from "../Icons/Hidden.vue";
import IconVisible from "../Icons/Visible.vue";
import { usePostStore } from "@/Stores/Post";
import { useUserStore } from "@/Stores/User";

import type { Post } from "../../types";
import type { PropType } from "vue";

export default defineComponent({

    name: "PostVisibilityControls",

    components: {
        IconHidden,
        IconVisible,
    },

    props: {
        post: {
            required: true,
            type    : Object as PropType<Post>,
        },
    },

    computed: {

        ...mapState(useUserStore, ["isAdmin"]),

    },

    methods: {

        ...mapActions(usePostStore, ["setIsHidden"]),

        async updateIsHidden(is_hidden: boolean): Promise<void> {
            if (this.post.id !== undefined) {
                await axios.put(
                    `/posts/${this.post.id}/visibility`,
                    { is_hidden },
                );

                this.setIsHidden(this.post.id, is_hidden);
            }
        },

    },

});

</script>
