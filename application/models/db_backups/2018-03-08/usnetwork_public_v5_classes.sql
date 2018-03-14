CREATE TABLE public.v5_classes
(
    r_id integer DEFAULT nextval('v5_challenges_timeline_id_seq'::regclass) PRIMARY KEY NOT NULL,
    r_min_students integer DEFAULT 1 NOT NULL,
    r_max_students integer,
    r_usd_price double precision,
    r_status smallint DEFAULT 0 NOT NULL,
    r_closed_dates text,
    r_start_date date NOT NULL,
    r_live_office_hours text,
    r_response_time_hours double precision,
    r_office_hour_instructions text,
    r_b_id integer DEFAULT 0 NOT NULL,
    r_cancellation_policy varchar(30) DEFAULT NULL::character varying,
    r_start_time_mins integer DEFAULT 600,
    r_meeting_frequency varchar(2) DEFAULT NULL::character varying,
    r_meeting_duration double precision DEFAULT 0.5 NOT NULL,
    r_fb_pixel_id bigint DEFAULT 0 NOT NULL,
    r_cache__current_milestone integer DEFAULT 0 NOT NULL,
    r_cache__end_time timestamp,
    r_cache__completion_rate double precision DEFAULT '-1'::integer NOT NULL,
    r_reply_to_email varchar(250) DEFAULT NULL::character varying
);
COMMENT ON COLUMN public.v5_classes.r_min_students IS 'Minimum number of students required to launch this cohort.';
COMMENT ON COLUMN public.v5_classes.r_max_students IS 'Maximum student that can enroll before cohort becomes full.';
COMMENT ON COLUMN public.v5_classes.r_usd_price IS 'How much this bootcamp costs in USD?';
COMMENT ON COLUMN public.v5_classes.r_status IS '-1 deleted, 1 drafting, 2 pending review, 3 live, 4 ended';
COMMENT ON COLUMN public.v5_classes.r_closed_dates IS 'A manual list of dates that the bootcamp would be closed.';
COMMENT ON COLUMN public.v5_classes.r_start_date IS 'The day that the cohort starts';
COMMENT ON COLUMN public.v5_classes.r_live_office_hours IS 'Defines the times throughout the week that live mentorship is available. JSON array with details.';
COMMENT ON COLUMN public.v5_classes.r_response_time_hours IS 'The expected response time to a student inquiry message. Options are 1,2,3,5,12,24,48, etc...';
COMMENT ON COLUMN public.v5_classes.r_b_id IS 'The bootcamp ID that this class belongs to';
COMMENT ON COLUMN public.v5_classes.r_start_time_mins IS 'when the instructor will have the intro session in minutes since midnight.';
COMMENT ON COLUMN public.v5_classes.r_meeting_frequency IS 'As defined in config r_meeting_frequency is a list of options that define how frequency the instructor team would offer meetings for this class.';
COMMENT ON COLUMN public.v5_classes.r_fb_pixel_id IS 'The big Int pixel ID that can help with Ad tracking.';
COMMENT ON COLUMN public.v5_classes.r_cache__current_milestone IS 'Holds a copy records of which milestone this class is at';
CREATE INDEX r_id ON public.v5_classes (r_id);
CREATE INDEX v5_challenges_timeline_id_idx ON public.v5_classes (r_id);
CREATE INDEX v5_challenge_runs_r_status_idx ON public.v5_classes (r_status);
CREATE INDEX v5_classes_r_start_date_index ON public.v5_classes (r_start_date);
CREATE INDEX v5_classes_r_b_id_index ON public.v5_classes (r_b_id);
CREATE INDEX v5_classes_r_cache__current_milestone_index ON public.v5_classes (r_cache__current_milestone);