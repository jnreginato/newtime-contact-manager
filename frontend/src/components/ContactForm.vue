<script setup lang="ts">
import {onMounted, ref} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import {useContactsStore} from '@/stores/contacts'
import type {Contact} from '@/types/contact'
import {createContact, getContactById, updateContact} from '@/lib/http'

const route = useRoute();
const router = useRouter();
const store = useContactsStore();

const form = ref<Contact>({
    id: 0,
    firstName: '',
    lastName: '',
    email: ''
});

const isEditing = route.params.id !== undefined;

onMounted(async () => {
    if (isEditing) {
        form.value = await getContactById(Number(route.params.id))
    }
});

async function save() {
    if (isEditing) {
        await updateContact(form.value.id, form.value)
    } else {
        await createContact(form.value)
    }

    await store.fetchContacts();
    await router.push('/contacts')
}
</script>
<template>
    <div class="max-w-xl mx-auto py-10 text-gray-100">
        <h1 class="text-2xl font-semibold mb-6">
            {{ isEditing ? 'Modifica Contatto' : 'Nuovo Contatto' }}
        </h1>
        <div class="space-y-4">
            <input v-model="form.firstName" type="text" placeholder="Nome" class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700"/>
            <input v-model="form.lastName" type="text" placeholder="Cognome" class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700"/>
            <input v-model="form.email" type="email" placeholder="Email" class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700"/>
        </div>
        <button
            @click="save"
            class="mt-6 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 transition-colors"
        >
            Salva
        </button>
    </div>
</template>
