<script setup lang="ts">
import {useRoute} from 'vue-router'
import ContactForm from "@/components/ContactForm.vue";

const route = useRoute()
</script>
<template>
    <div class="min-h-screen bg-gray-900 text-gray-100 font-sans">
        <div class="max-w-6xl mx-auto px-6 py-12">
            <div class="p-6">
                <ContactForm :id="Number(route.params.id)"/>
            </div>
        </div>
    </div>
</template>
