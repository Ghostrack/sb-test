<template>
    <div>
        <template
            v-for="post in posts"
            :key="`post-display-card-${post.id}`"
        >
            <post-display-card
                :post="post"
                class="my-5 mx-28"
            />
        </template>
    </div>
</template>

<script lang="ts">

import { defineComponent } from "vue";
import { mapState } from "pinia";
import PostDisplayCard from "./PostDisplayCard.vue";
import { usePostStore } from "@/Stores/Post";

export default defineComponent({

    name: "PostList",

    components: {
        PostDisplayCard,
    },

    computed: {
        ...mapState(usePostStore, ["posts"]),
    },

});

</script>
