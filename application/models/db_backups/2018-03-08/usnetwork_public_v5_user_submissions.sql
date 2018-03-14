CREATE TABLE public.v5_user_submissions
(
    us_id integer DEFAULT nextval('v5_user_submissions_us_id_seq'::regclass) PRIMARY KEY NOT NULL,
    us_student_id integer NOT NULL,
    us_timestamp timestamp NOT NULL,
    us_b_id integer NOT NULL,
    us_c_id integer NOT NULL,
    us_student_notes text,
    us_status smallint NOT NULL,
    us_r_id integer DEFAULT 0 NOT NULL,
    us_time_estimate double precision DEFAULT 0 NOT NULL
);
COMMENT ON COLUMN public.v5_user_submissions.us_time_estimate IS 'The cache copy of the time estimate of the task upon submission.';
CREATE INDEX v5_user_submissions_us_student_id_index ON public.v5_user_submissions (us_student_id);
CREATE INDEX v5_user_submissions_us_b_id_index ON public.v5_user_submissions (us_b_id);
CREATE INDEX v5_user_submissions_us_c_id_index ON public.v5_user_submissions (us_c_id);
CREATE INDEX v5_user_submissions_us_status_index ON public.v5_user_submissions (us_status);