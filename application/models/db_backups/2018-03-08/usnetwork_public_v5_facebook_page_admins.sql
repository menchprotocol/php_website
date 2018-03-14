CREATE TABLE public.v5_facebook_page_admins
(
    fs_id integer DEFAULT nextval('v5_facebook_page_admins_fa_id_seq'::regclass) PRIMARY KEY NOT NULL,
    fs_timestamp timestamp NOT NULL,
    fs_fp_id integer NOT NULL,
    fs_u_id integer NOT NULL,
    fs_status smallint NOT NULL,
    fs_access_token text
);
CREATE INDEX v5_facebook_page_admins_fs_timestamp_index ON public.v5_facebook_page_admins (fs_timestamp);
CREATE INDEX v5_facebook_page_admins_fa_fp_id_index ON public.v5_facebook_page_admins (fs_fp_id);
CREATE INDEX v5_facebook_page_admins_fa_u_id_index ON public.v5_facebook_page_admins (fs_u_id);
CREATE INDEX v5_facebook_page_admins_fa_status_index ON public.v5_facebook_page_admins (fs_status);