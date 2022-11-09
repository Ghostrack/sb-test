import { acceptHMRUpdate, defineStore } from "pinia";

import type { Post } from "@/types";

export const usePostStore = defineStore("post", {
    actions: {

        addToFavourites(id: number): void {
            const index = this.findIndexById(id);

            this.posts[index].is_favourite = true;
            this.posts[index].favourite_count += 1;
        },

        findIndexById(id: number): number {
            return this.posts.findIndex((item) => item.id === id);
        },

        removeFromFavourites(id: number): void {
            const index = this.findIndexById(id);

            this.posts[index].is_favourite = false;
            this.posts[index].favourite_count -= 1;
        },

        removePost(id: number): void {
            const index = this.findIndexById(id);

            this.posts.splice(index, 1);
        },

        setFavourites(favouriteIds: number[]): void {
            for (const id of favouriteIds) {
                this.updateProperty(id, "is_favourite", true);
            }
        },

        setIsHidden(id: number, is_hidden: boolean): void {
            this.updateProperty(id, "is_hidden", is_hidden);
        },

        setPosts(posts: Post[]): void {
            this.posts = [...posts];
        },

        updateProperty(id: number, key: string, value: boolean | number | string): void {
            const index = this.findIndexById(id);

            this.posts[index][key] = value;
        },

    },
    getters: {
    },
    state: () => ({
        posts: [] as Post[],
    }),
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(usePostStore, import.meta.hot))
}
