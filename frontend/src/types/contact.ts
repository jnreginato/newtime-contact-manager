export interface Contact {
  id: number;
  firstName: string;
  lastName: string;
  email: string;
}

export interface Paginated<T> {
  data: T[];
  meta: {
    count: number;
    currentPage: number;
    perPage: number;
    totalPages: number;
    totalItems: number;
  };
}
