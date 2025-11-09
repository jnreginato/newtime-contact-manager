<script setup lang="ts">
const props = defineProps<{
    title?: string;
    message?: string;
    confirmText?: string;
    cancelText?: string;
    show: boolean;
}>();

const emit = defineEmits<{
    (e: 'confirm'): void;
    (e: 'cancel'): void;
}>();
</script>
<template>
    <transition name="fade">
        <div
            v-if="show"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"
        >
            <div class="bg-gray-900 border border-gray-700 rounded-xl p-6 w-full max-w-sm shadow-xl">
                <h2 class="text-lg font-semibold text-gray-100">
                    {{ title ?? "Conferma Azione" }}
                </h2>
                <p class="text-gray-400 text-sm mt-2"> {{ message ?? "Sei sicuro di procedere?" }} </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <button
                        @click="emit('cancel')"
                        class="px-4 py-2 rounded-lg border border-gray-700 text-gray-300 hover:bg-gray-800 transition"
                    >
                        {{ cancelText ?? "Annulla" }}
                    </button>
                    <button
                        @click="emit('confirm')"
                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white transition"
                    >
                        {{ confirmText ?? "Elimina" }}
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity .2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
