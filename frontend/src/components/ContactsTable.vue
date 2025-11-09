<script setup lang="ts">
import { onMounted, ref } from 'vue';
import type { Contact, Paginated } from '@/types/contact';
import { getContacts } from '@/lib/http';

const loading = ref(true);
const error = ref<string | null>(null);
const contacts = ref<Contact[]>([]);

onMounted(async () => {
    try {
        const resp = (await getContacts()) as Paginated<Contact>;
        contacts.value = resp.data ?? [];
    } catch (e: any) {
        error.value = e?.message ?? 'Erro ao carregar contatos';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="min-h-screen bg-gray-900 text-gray-100 font-sans">

        <div class="max-w-6xl mx-auto px-6 py-12">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-semibold">Contatti</h1>
                    <p class="text-gray-400 text-sm mt-1">
                        Lista dei contatti registrati
                    </p>
                </div>

                <button
                    class="px-4 py-2 rounded-lg text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-500 transition-colors"
                >
                    Nuovo Contatto
                </button>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="text-gray-400 animate-pulse">
                Caricamento…
            </div>

            <!-- Erro -->
            <div v-else-if="error" class="text-red-400 font-medium">
                {{ error }}
            </div>

            <!-- Lista -->
            <div v-else>
                <!-- Vazio -->
                <div
                    v-if="contacts.length === 0"
                    class="rounded-lg border border-gray-700 p-10 text-center text-gray-400 italic"
                >
                    Nessun contatto trovato.
                </div>

                <!-- Tabela -->
                <div v-else class="overflow-hidden rounded-lg border border-gray-800">
                    <table class="min-w-full divide-y divide-gray-800 text-sm">
                        <thead class="text-gray-400 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium">Nome</th>
                                <th class="px-4 py-3 text-left font-medium">Cognome</th>
                                <th class="px-4 py-3 text-left font-medium">Email</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-200 divide-y divide-gray-800">
                            <tr
                                v-for="c in contacts"
                                :key="c.id"
                                class="hover:bg-gray-800/60 transition-colors"
                            >
                                <td class="px-4 py-4">{{ c.firstName }}</td>
                                <td class="px-4 py-4">{{ c.lastName }}</td>
                                <td class="px-4 py-4">
                                    <a
                                        :href="`mailto:${c.email}`"
                                        class="text-indigo-400 hover:underline"
                                    >
                                        {{ c.email }}
                                    </a>
                                </td>

                                <td class="px-4 py-4 text-right">
                                    <button
                                        class="text-indigo-400 hover:text-indigo-300 transition-colors text-sm font-medium"
                                    >
                                        Modifica
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
