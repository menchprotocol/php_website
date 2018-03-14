CREATE TABLE public.v5_message_fb_sync
(
    sy_id integer DEFAULT nextval('v5_message_attachments_im_id_seq'::regclass) PRIMARY KEY NOT NULL,
    sy_timestamp timestamp NOT NULL,
    sy_i_id integer NOT NULL,
    sy_fp_id integer NOT NULL,
    sy_fb_att_id bigint NOT NULL
);
CREATE UNIQUE INDEX v5_message_attachments_im_id_uindex ON public.v5_message_fb_sync (sy_id);
CREATE INDEX v5_message_attachments_im_i_id_index ON public.v5_message_fb_sync (sy_i_id);
CREATE INDEX v5_message_attachments_im_fp_id_index ON public.v5_message_fb_sync (sy_fp_id);