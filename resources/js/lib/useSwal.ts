import { getCurrentInstance } from "vue";
import type { SweetAlertOptions } from "sweetalert2";

export function useSwal() {
    const { proxy } = getCurrentInstance()!;
    const swal = proxy.$swal;

    const fire = (
        options: SweetAlertOptions | string,
        icon?: "success" | "error" | "warning" | "info" | "question"
    ) => {
        if (typeof options === "string") {
            return swal({ title: options, icon: icon });
        }
        return swal({
            ...options,
            icon: icon ?? options.icon, // icon bisa override atau pakai bawaan
        });
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

    // shortcut helper
    const success = (msg: string, title = "Berhasil") =>
        fire({ title, text: msg }, "success");

    const error = (msg: string, title = "Gagal") =>
        fire({ title, text: msg }, "error");

    return { fire, confirm, toast, error, success };
}
