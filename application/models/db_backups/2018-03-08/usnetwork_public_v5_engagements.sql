CREATE TABLE public.v5_engagements
(
    e_id integer DEFAULT nextval('v5_engagement_id_seq'::regclass) PRIMARY KEY NOT NULL,
    e_initiator_u_id integer NOT NULL,
    e_timestamp timestamp NOT NULL,
    e_message text,
    e_type_id integer NOT NULL,
    e_b_id integer DEFAULT 0 NOT NULL,
    e_cron_job smallint DEFAULT '-1'::integer NOT NULL,
    e_r_id integer DEFAULT 0 NOT NULL,
    e_recipient_u_id integer DEFAULT 0 NOT NULL,
    e_c_id integer DEFAULT 0 NOT NULL,
    e_cr_id integer DEFAULT 0 NOT NULL,
    e_i_id integer DEFAULT 0 NOT NULL,
    e_has_blob boolean DEFAULT false NOT NULL,
    e_fp_id integer DEFAULT 0 NOT NULL
);
COMMENT ON COLUMN public.v5_engagements.e_initiator_u_id IS 'The ID of the user who initiated this engagement. 0 means system.';
COMMENT ON COLUMN public.v5_engagements.e_type_id IS 'What is the type of engagement is this? Referencing the table e_engagement_types';
COMMENT ON COLUMN public.v5_engagements.e_b_id IS 'If set, would give visibility to this engagement via the Bootcamp Activity Stream.';
COMMENT ON COLUMN public.v5_engagements.e_cron_job IS 'A field that indicates if an engagement needs a cron job to do something (Like upload an image from Facebook to our AWS server). Here are what these statuses mean:

-1 = No cron job
0 = Has cron job, pending completion
1 = Cron job completed';
COMMENT ON COLUMN public.v5_engagements.e_r_id IS 'If this engagements maps to a class, this value would be >0';
COMMENT ON COLUMN public.v5_engagements.e_recipient_u_id IS 'If this engagement has a receiver user, this value would be >0.';
COMMENT ON COLUMN public.v5_engagements.e_c_id IS 'If this engagement is related to a specific intent, this value would be >0';
COMMENT ON COLUMN public.v5_engagements.e_cr_id IS 'If this engagement is related to a specific intent link, this value would be >0';
COMMENT ON COLUMN public.v5_engagements.e_i_id IS 'If this engagement is related to a specific insight, this field would be >0';
CREATE INDEX e_id ON public.v5_engagements (e_id);
CREATE INDEX e_initiator_u_id ON public.v5_engagements (e_initiator_u_id);
CREATE INDEX e_timestamp_desc ON public.v5_engagements (e_timestamp DESC);
CREATE INDEX v5_engagements_e_timestamp_index ON public.v5_engagements (e_timestamp);
CREATE INDEX e_type_id ON public.v5_engagements (e_type_id);
CREATE INDEX e_b_id ON public.v5_engagements (e_b_id);
CREATE INDEX e_cron_job ON public.v5_engagements (e_cron_job);
CREATE INDEX e_r_id ON public.v5_engagements (e_r_id);
CREATE INDEX e_recipient_u_id ON public.v5_engagements (e_recipient_u_id);
CREATE INDEX v5_engagements_e_recipient_u_id_index ON public.v5_engagements (e_recipient_u_id);
CREATE INDEX e_c_id ON public.v5_engagements (e_c_id);
CREATE INDEX e_cr_id ON public.v5_engagements (e_cr_id);
CREATE INDEX e_i_id ON public.v5_engagements (e_i_id);
CREATE INDEX v5_engagements_e_has_blob_index ON public.v5_engagements (e_has_blob);
CREATE INDEX v5_engagements_e_fp_id_index ON public.v5_engagements (e_fp_id);