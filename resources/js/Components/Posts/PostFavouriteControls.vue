<template>
    <span class="mr-2 text-xl">
        {{ post.favourite_count }}
    </span>
    <icon-star-empty
        v-if="!post.is_favourite"
        class="cursor-pointer w-8"
        @click="favourite()"
    />
    <icon-star-full
        v-else
        class="cursor-pointer w-8 fill-yellow-500"
        @click="unfavourite()"
    />
</template>

<script lang="ts">

import { mapActions } from "pinia";

import axios from "axios";
import { defineComponent } from "vue";
import IconStarEmpty from "../Icons/StarEmpty.vue";
import IconStarFull from "../Icons/StarFull.vue";
import { usePostStore } from "@/Stores/Post";

import type { Post } from "../../types";
import type { PropType } from "vue";

export default defineComponent({

    name: "PostVisibilityControls",

    components: {
        IconStarEmpty,
        IconStarFull,
    },

    props: {
        post: {
            required: true,
            type    : Object as PropType<Post>,
        },
    },

    methods: {

        ...mapActions(usePostStore, [
            "addToFavourites",
            "removeFromFavourites",
        ]),

        async favourite(): Promise<void> {
            if (this.post.id !== undefined) {
                await axios.post(`/posts/${this.post.id}/favourite`);

                this.addToFavourites(this.post.id);
            }
        },

        async unfavourite(): Promise<void> {
            if (this.post.id !== undefined) {
                await axios.delete(`/posts/${this.post.id}/favourite`);

                this.removeFromFavourites(this.post.id);
            }
        },

    },

});

</script>
