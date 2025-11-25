import { getCurrentInstance } from "vue";

export function useSwal() {
    const { proxy } = getCurrentInstance()!;
    const swal = proxy.$swal;

    const fire = (options: SweetAlertOptions | string) => {
        if (typeof options === "string") {
            return swal(options);
        }
        return swal(options);
    };

    const confirm = async (
        message = "Yakin?",
        options: SweetAlertOptions = {}
    ) => {
        const result = await swal({
            title: "Konfirmasi",
            text: message,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Batal",
            reverseButtons: true,
            ...options,
        });
        return result.isConfirmed;
    };

    const toast = (
        title: string,
        icon: "success" | "error" | "info" | "warning"
    ) => {
        return swal
            .mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
            })
            .fire({ title, icon });
    };

    return { fire, confirm, toast };
}
