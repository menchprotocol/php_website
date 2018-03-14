CREATE TABLE public.v5_facebook_pages
(
    fp_id integer DEFAULT nextval('v5_bots_bo_id_seq'::regclass) PRIMARY KEY NOT NULL,
    fp_timestamp timestamp NOT NULL,
    fp_fb_id bigint NOT NULL,
    fp_name varchar(100) NOT NULL,
    fp_status smallint DEFAULT 1 NOT NULL,
    fp_greeting varchar(180) DEFAULT NULL::character varying
);
CREATE UNIQUE INDEX v5_facebook_pages_fp_fb_id_uindex ON public.v5_facebook_pages (fp_fb_id);
CREATE INDEX v5_facebook_pages_fp_fb_id_index ON public.v5_facebook_pages (fp_fb_id);
CREATE INDEX v5_facebook_pages_fp_status_index ON public.v5_facebook_pages (fp_status);