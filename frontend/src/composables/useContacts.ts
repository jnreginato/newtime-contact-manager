import {ref} from 'vue'
import type {Contact, Paginated} from '@/types/contact'
import {getContacts} from '@/lib/http'

export function useContacts() {
  const loading = ref(true);
  const error = ref<string | null>(null);
  const contacts = ref<Contact[]>([]);
  const meta = ref({
    count: 0,
    currentPage: 1,
    perPage: 10,
    totalPages: 1,
    totalItems: 0,
  });

  async function fetchContacts(
    page = meta.value.currentPage,
    size = meta.value.perPage,
  ) {
    loading.value = true;
    error.value = null;

    try {
      const resp = (await getContacts({
        pageNumber: page,
        pageSize: size
      })) as Paginated<Contact>;

      contacts.value = resp.data ?? [];
      meta.value = resp.meta ?? meta.value
    } catch (e: any) {
      error.value = e?.message ?? 'Errore durante il caricamento dei contatti'
    } finally {
      loading.value = false
    }
  }

  function goToPage(page: number) {
    if (page < 1 || page > meta.value.totalPages) {
      return;
    }
    fetchContacts(page, meta.value.perPage);
  }

  return {
    loading,
    error,
    contacts,
    meta,
    fetchContacts,
    goToPage,
  }
}
