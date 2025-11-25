import * as icons from "lucide-vue-next";

export function useIcon(name?: string) {
    if (!name) return null;
    return (icons as any)[name] ?? null;
}
