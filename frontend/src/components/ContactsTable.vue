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
    <div class="max-w-6xl mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4">Contatti</h1>

        <div v-if="loading" class="text-gray-600">Caricamento…</div>
        <div v-else-if="error" class="text-red-600">{{ error }}</div>

        <div v-else>
            <div v-if="contacts.length === 0" class="p-6 rounded border border-dashed text-gray-600">
                Nessun contatto trovato.
            </div>

            <div v-else class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left p-3 border-b">Nome</th>
                            <th class="text-left p-3 border-b">Cognome</th>
                            <th class="text-left p-3 border-b">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in contacts" :key="c.id" class="odd:bg-white even:bg-gray-50">
                            <td class="p-3 border-b">{{ c.firstName }}</td>
                            <td class="p-3 border-b">{{ c.lastName }}</td>
                            <td class="p-3 border-b">
                                <a :href="`mailto:${c.email}`" class="underline">{{ c.email }}</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* usa Tailwind se já estiver; se não, estas classes funcionam com CSS utilitário simples */
</style>
