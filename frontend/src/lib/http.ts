import {Contact} from "@/types/contact";

const BASE_URL = import.meta.env.VITE_API_BASE_URL ?? '';

export async function getContacts(params?: {
  pageNumber?: number;
  pageSize?: number;
}) {
  const qs = new URLSearchParams();

  if (params?.pageNumber) {
    qs.set('page[number]', String(params.pageNumber));
  }
  if (params?.pageSize) {
    qs.set('page[size]', String(params.pageSize));
  }

  const url = `${BASE_URL}/api/v1/contacts${qs.toString() ? `?${qs}` : ''}`;

  const res = await fetch(url, { headers: { Accept: 'application/json' } });

  if (!res.ok) {
    const text = await res.text().catch(() => '');
    throw new Error(`GET /contacts failed: ${res.status} ${text}`);
  }

  return res.json();
}

export async function getContactById(id: number) {
  const res = await fetch(`${BASE_URL}/api/v1/contacts/${id}`);
  return res.json()
}

export async function createContact(body: Omit<Contact, 'id'>) {
  const res = await fetch(`${BASE_URL}/api/v1/contacts`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body)
  });
  return res.json()
}

export async function updateContact(id: number, body: Omit<Contact, 'id'>) {
  const res = await fetch(`${BASE_URL}/api/v1/contacts/${id}`, {
    method: 'PATCH',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body)
  });
  return res.json()
}
