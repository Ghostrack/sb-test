export interface Form {
    errors?: Record<string, string>;
}

export interface Post {
    content: string;
    created_at?: string;
    favourite_count: number;
    id?: number;
    is_favourite: boolean;
    is_hidden: boolean;
    title: string;
    user?: User;
    updated_at?: string;
}

export interface User {
    id: number;
    is_admin: boolean;
    name: string;
}
