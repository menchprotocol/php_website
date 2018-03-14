CREATE TABLE public.v5_intents
(
    c_id integer DEFAULT nextval('v5_challenges_id_seq'::regclass) PRIMARY KEY NOT NULL,
    c_creator_id integer NOT NULL,
    c_timestamp timestamp NOT NULL,
    c_objective varchar(250) NOT NULL,
    c_status smallint DEFAULT 1 NOT NULL,
    c_algolia_id bigint DEFAULT 0 NOT NULL,
    c_time_estimate double precision DEFAULT 0 NOT NULL,
    c_duration_multiplier smallint DEFAULT 1 NOT NULL,
    c_complete_url_required boolean DEFAULT false NOT NULL,
    c_complete_notes_required boolean DEFAULT false NOT NULL,
    c_complete_is_bonus_task boolean DEFAULT false NOT NULL
);
COMMENT ON COLUMN public.v5_intents.c_objective IS 'The primary goal of this bootcamp.';
COMMENT ON COLUMN public.v5_intents.c_status IS '1 enrolled, 2 completed';
COMMENT ON COLUMN public.v5_intents.c_time_estimate IS 'The estimated time it takes to execute this specific bootcamp, without considering its related bootcamps linked via the wiki curriculum.';
CREATE INDEX v5_challenges_id_idx ON public.v5_intents (c_id);
CREATE INDEX v5_challenges_creator_id_idx ON public.v5_intents (c_creator_id);
CREATE INDEX v5_challenges_status_idx ON public.v5_intents (c_status);