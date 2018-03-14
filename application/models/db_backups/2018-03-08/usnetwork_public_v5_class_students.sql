CREATE TABLE public.v5_class_students
(
    ru_id integer DEFAULT nextval('v5_enrollment_id_seq'::regclass) PRIMARY KEY NOT NULL,
    ru_r_id integer NOT NULL,
    ru_status smallint DEFAULT 0 NOT NULL,
    ru_u_id integer NOT NULL,
    ru_timestamp timestamp DEFAULT now() NOT NULL,
    ru_application_survey text,
    ru_affiliate_u_id integer DEFAULT 0 NOT NULL,
    ru_review_score smallint DEFAULT '-1'::integer NOT NULL,
    ru_review_public_note text,
    ru_review_private_note text,
    ru_review_time timestamp,
    ru_cache__completion_rate double precision DEFAULT 0.00 NOT NULL,
    ru_cache__current_milestone integer DEFAULT 1 NOT NULL,
    ru_cache__current_task integer DEFAULT 1 NOT NULL,
    ru_fp_id integer,
    ru_fp_psid bigint
);
COMMENT ON COLUMN public.v5_class_students.ru_id IS 'Auto-incremented. The primary student admission ID.';
COMMENT ON COLUMN public.v5_class_students.ru_r_id IS 'Maps to v5_classes which indicates which class this student has registered in.';
COMMENT ON COLUMN public.v5_class_students.ru_status IS 'The admission status of the student for the cohort. For more details on statuses visit: https://mench.co/console/help/status_bible';
COMMENT ON COLUMN public.v5_class_students.ru_u_id IS 'The ID of the student who is applied to join this class.';
COMMENT ON COLUMN public.v5_class_students.ru_timestamp IS 'The timestamp the student applied to join this class.';
COMMENT ON COLUMN public.v5_class_students.ru_application_survey IS 'To join a class, students must submit an application. This field stores the JSON encoded answers of the student''s application.';
COMMENT ON COLUMN public.v5_class_students.ru_affiliate_u_id IS 'If this user registered through an affiliate this would have their user ID.';
COMMENT ON COLUMN public.v5_class_students.ru_review_score IS 'The score from 1-10 that the student would give to their Class experience';
COMMENT ON COLUMN public.v5_class_students.ru_review_public_note IS 'The Public review that the student leaves for the instructor and their Class experience that would be used on the Class Landing Page';
COMMENT ON COLUMN public.v5_class_students.ru_review_private_note IS 'The private review note that the student leaves for the instructor';
CREATE INDEX v5_challenge_users_id_idx ON public.v5_class_students (ru_id);
CREATE INDEX ru_r_id ON public.v5_class_students (ru_r_id);
CREATE INDEX v5_challenge_users_challenge_id_idx ON public.v5_class_students (ru_r_id);
CREATE INDEX v5_challenge_users_status_idx ON public.v5_class_students (ru_status);
CREATE INDEX ru_u_id ON public.v5_class_students (ru_u_id);
CREATE INDEX v5_challenge_users_user_id_idx ON public.v5_class_students (ru_u_id);
CREATE INDEX v5_class_students_ru_timestamp_index ON public.v5_class_students (ru_timestamp);
CREATE INDEX v5_class_students_ru_review_score_index ON public.v5_class_students (ru_review_score);
CREATE INDEX v5_class_students_ru_cache__completion_rate_index ON public.v5_class_students (ru_cache__completion_rate);
CREATE INDEX v5_class_students_ru_cache__current_milestone_index ON public.v5_class_students (ru_cache__current_milestone);
CREATE INDEX v5_class_students_ru_cache__current_task_index ON public.v5_class_students (ru_cache__current_task);
CREATE INDEX v5_class_students_ru_cache__fb_page_id_index ON public.v5_class_students (ru_fp_id);
CREATE INDEX v5_class_students_ru_cache__fb_user_id_index ON public.v5_class_students (ru_fp_psid);