import {z} from "zod";

export const contactSchema = z.object({
  firstName: z.string().min(1, "Nome obbligatorio"),
  lastName: z.string().min(1, "Cognome obbligatorio"),
  email: z.string().email("Indirizzo e-mail non valido")
});

export type ContactForm = z.infer<typeof contactSchema>;
