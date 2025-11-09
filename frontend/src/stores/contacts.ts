import {defineStore} from 'pinia'
import {ref} from 'vue'
import type {Contact, Paginated} from '@/types/contact'
import {getContacts} from '@/lib/http'

export const useContactsStore = defineStore('contacts', () => {
  const loading = ref(false);
  const error = ref<string | null>(null);
  const contacts = ref<Contact[]>([]);
  const meta = ref({
    currentPage: 1,
    perPage: 10,
    totalPages: 1,
    totalItems: 0,
    count: 0
  });

  async function fetchContacts(page?: number) {
    loading.value = true;
    error.value = null;

    const pageNumber = page ?? meta.value.currentPage;

    try {
      const resp = (await getContacts({
        pageNumber,
        pageSize: meta.value.perPage
      })) as Paginated<Contact>;

      contacts.value = resp.data;
      meta.value = resp.meta
    } catch (e: any) {
      error.value = e?.message ?? 'Erro ao carregar contatos'
    } finally {
      loading.value = false
    }
  }

  function goToPage(page: number) {
    if (page < 1 || page > meta.value.totalPages) {
      return;
    }
    meta.value.currentPage = page;
    fetchContacts(page)
  }

  return {loading, error, contacts, meta, fetchContacts, goToPage}
}, {
  persist: {
    storage: localStorage,
  }
});
