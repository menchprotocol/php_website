CREATE TABLE public.v5_bootcamps
(
    b_id integer DEFAULT nextval('v5_bootcamps_b_id_seq2'::regclass) PRIMARY KEY NOT NULL,
    b_timestamp timestamp NOT NULL,
    b_creator_id integer NOT NULL,
    b_c_id integer NOT NULL,
    b_status smallint DEFAULT 0 NOT NULL,
    b_url_key varchar(255) NOT NULL,
    b_algolia_id bigint DEFAULT 0,
    b_sprint_unit varchar(20) DEFAULT 'week'::character varying NOT NULL,
    b_target_audience text,
    b_prerequisites text,
    b_application_questions text,
    b_transformations text,
    b_completion_prizes text,
    b_fp_id integer DEFAULT 0 NOT NULL
);
COMMENT ON COLUMN public.v5_bootcamps.b_id IS 'Auto-incremented. The primary Bootcamp ID. Used to load the Bootcamps in Console URL.';
COMMENT ON COLUMN public.v5_bootcamps.b_timestamp IS 'Timestamp when bootcamp was created. Will never change.';
COMMENT ON COLUMN public.v5_bootcamps.b_creator_id IS 'Maps to v5_users.u_id and indicates the user that created this bootcamp. This does NOT define any access privileges, as it only tracks who created this bootcamp. Bootcamp access priveleges managed using v5_bootcamp_admins';
COMMENT ON COLUMN public.v5_bootcamps.b_c_id IS 'Maps to v5_intents.c_id as every Bootcamp is a 1-to-1 mirror of an Intent in v5_intents. The Bootcamp title is equal to c_objective which is the title of the intent.';
COMMENT ON COLUMN public.v5_bootcamps.b_status IS 'Indicates the bootcamp status. For more details on statuses visit: https://mench.co/console/help/status_bible';
COMMENT ON COLUMN public.v5_bootcamps.b_url_key IS 'The URL key of the bootcamp''s landing page used to load the bootcamp for the user on the front website.';
COMMENT ON COLUMN public.v5_bootcamps.b_algolia_id IS 'We use algolia.com for search, and this field is a mirror of the algolia ID so we can sync each bootcamp with it''s algolia obect for searching.';
COMMENT ON COLUMN public.v5_bootcamps.b_sprint_unit IS 'EIther ''day'' or ''week''';
CREATE INDEX b_id ON public.v5_bootcamps (b_id);
CREATE INDEX v5_bootcamps_b_c_id_index ON public.v5_bootcamps (b_c_id);
CREATE INDEX v5_bootcamps_b_status_index ON public.v5_bootcamps (b_status);
CREATE INDEX v5_bootcamps_b_url_key_index ON public.v5_bootcamps (b_url_key);