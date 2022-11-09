import { acceptHMRUpdate, defineStore } from "pinia";

import type { User } from "@/types";

export const useUserStore = defineStore("user", {
    actions: {
        setUser(user: User): void {
            this.user = {...user};
        },
    },
    getters: {
        isAdmin(): boolean {
            return Boolean(this.user?.is_admin);
        },
        isAuthenticated(): boolean {
            return Boolean(this.user?.id);
        },
    },
    state: () => ({
        user: undefined as User | undefined,
    }),
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useUserStore, import.meta.hot))
}
