import * as icons from "lucide-vue-next";

export const allowedIcons = ["Home", "Settings", "Users", "Menu", "Bell"];

export function useIcon(name?: string) {
    if (!name) return null;
    return (icons as any)[name] ?? null;
}

export const iconList = allowedIcons.map((name) => ({
    name,
    component: icons[name],
}));
