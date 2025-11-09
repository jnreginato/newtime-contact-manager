<script setup lang="ts">
import {onMounted} from 'vue'
import {useContactsStore} from '@/stores/contacts'
import { RouterLink } from 'vue-router'

const store = useContactsStore();

onMounted(() => {
    store.fetchContacts()
})
</script>
<button class="hidden" />
<template>
    <div class="min-h-screen bg-gray-900 text-gray-100 font-sans">
        <div class="max-w-6xl mx-auto px-6 py-12">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-semibold">Contatti</h1>
                    <p class="text-gray-400 text-sm mt-1"> Lista dei contatti registrati </p>
                </div>
                <RouterLink
                    to="/contacts/new"
                    class="px-4 py-2 rounded-lg text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-500 transition-colors"
                >
                    Nuovo Contatto
                </RouterLink>
            </div>
            <!-- Loading -->
            <div v-if="store.loading" class="text-gray-400 animate-pulse">
                Caricamento…
            </div>
            <!-- Error -->
            <div v-else-if="store.error" class="text-red-400 font-medium">
                {{ store.error }}
            </div>
            <!-- List -->
            <div v-else>
                <div v-if="store.contacts.length === 0" class="rounded-lg border border-gray-700 p-10 text-center text-gray-400 italic">
                    Nessun contatto trovato.
                </div>
                <!-- Table -->
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
                            <tr v-for="c in store.contacts" :key="c.id" class="hover:bg-gray-800/60 transition-colors">
                                <td class="px-4 py-4">{{ c.firstName }}</td>
                                <td class="px-4 py-4">{{ c.lastName }}</td>
                                <td class="px-4 py-4">
                                    <a :href="`mailto:${c.email}`" class="text-indigo-400 hover:underline">
                                        {{ c.email }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <RouterLink
                                        :to="`/contacts/${c.id}/edit`"
                                        class="text-indigo-400 hover:text-indigo-300 transition-colors text-sm font-medium"
                                    >
                                        Modifica
                                    </RouterLink>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="flex items-center justify-between mt-6">
                    <!-- Total -->
                    <p class="text-sm text-gray-400"> Mostrando pagina {{ store.meta.currentPage }} di {{ store.meta.totalPages }} — {{ store.meta.totalItems }} risultati </p>
                    <!-- Controls -->
                    <div class="flex items-center space-x-2">
                        <!-- Previous -->
                        <button
                            @click="store.goToPage(store.meta.currentPage - 1)"
                            :disabled="store.meta.currentPage === 1"
                            class="px-3 py-1 text-sm rounded-lg border border-gray-700 hover:bg-gray-800 disabled:opacity-30"
                        >
                            ‹
                        </button>
                        <!-- Dynamic navigation -->
                        <button
                            v-for="page in store.meta.totalPages"
                            :key="page"
                            @click="store.goToPage(page)"
                            class="px-3 py-1 text-sm rounded-lg border"
                            :class="page === store.meta.currentPage
                                ? 'bg-indigo-600 border-indigo-600 text-white'
                                : 'border-gray-700 hover:bg-gray-800'"
                        >
                            {{ page }}
                        </button>
                        <!-- Next -->
                        <button
                            @click="store.goToPage(store.meta.currentPage + 1)"
                            :disabled="store.meta.currentPage === store.meta.totalPages"
                            class="px-3 py-1 text-sm rounded-lg border border-gray-700 hover:bg-gray-800 disabled:opacity-30"
                        >
                            ›
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
