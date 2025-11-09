<script setup lang="ts">
import {onMounted, ref} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import {useContactsStore} from '@/stores/contacts'
import {createContact, getContactById, updateContact} from '@/lib/http'
import {useForm, Field, ErrorMessage} from "vee-validate";
import {toTypedSchema} from "@vee-validate/zod";
import {contactSchema} from "@/validation/contactSchema";

const route = useRoute();
const router = useRouter();
const store = useContactsStore();

const isEditing = route.params.id !== undefined;

interface ContactFormInput {
    firstName: string;
    lastName: string;
    email: string;
}

const {handleSubmit, setValues} = useForm<ContactFormInput>({
    validationSchema: toTypedSchema(contactSchema),
});

onMounted(async () => {
    if (isEditing) {
        const contact = await getContactById(Number(route.params.id));
        setValues({
            firstName: contact.firstName,
            lastName: contact.lastName,
            email: contact.email,
        });
    }
});

const isSubmitting = ref(false);

const submit = handleSubmit(async (values) => {
    try {
        isSubmitting.value = true;
        if (isEditing) {
            await updateContact(Number(route.params.id), {
                ...values
            });
        } else {
            await createContact(values);
        }

        await store.fetchContacts();
        router.push('/contacts');
    } finally {
        isSubmitting.value = false;
    }
});

</script>
<template>
    <form @submit.prevent="submit" class="max-w-xl mx-auto py-10 text-gray-100 space-y-6">
        <h1 class="text-2xl font-semibold">
            {{ isEditing ? 'Modifica Contatto' : 'Nuovo Contatto' }}
        </h1>

        <!-- Nome -->
        <div>
            <Field
                name="firstName"
                type="text"
                placeholder="Nome"
                class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700"
            />
            <ErrorMessage name="firstName" class="text-red-400 text-sm mt-1" />
        </div>

        <!-- Cognome -->
        <div>
            <Field
                name="lastName"
                type="text"
                placeholder="Cognome"
                class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700"
            />
            <ErrorMessage name="lastName" class="text-red-400 text-sm mt-1" />
        </div>

        <!-- Email -->
        <div>
            <Field
                name="email"
                type="email"
                placeholder="Email"
                class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700"
            />
            <ErrorMessage name="email" class="text-red-400 text-sm mt-1" />
        </div>

        <!-- Submit button -->
        <button
            type="submit"
            :disabled="isSubmitting"
            class="mt-4 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
            {{ isSubmitting ? "Salvando…" : "Salva" }}
        </button>
    </form>
</template>
