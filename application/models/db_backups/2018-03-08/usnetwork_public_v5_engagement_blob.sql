CREATE TABLE public.v5_engagement_blob
(
    ej_e_id integer NOT NULL,
    ej_e_blob text NOT NULL
);
CREATE UNIQUE INDEX v5_engagement_blob_ej_e_id_uindex ON public.v5_engagement_blob (ej_e_id);