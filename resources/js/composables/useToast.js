import { ref } from "vue";

const toasts = ref([]);

export function useToast() {
  const showToast = (message, error = false) => {
    const id = Date.now();
    toasts.value.push({ id, message, error });

    setTimeout(() => {
      toasts.value = toasts.value.filter((toast) => toast.id !== id);
    }, 3000);
  };

  return { toasts, showToast };
}
