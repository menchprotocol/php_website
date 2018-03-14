CREATE TABLE public.v5_scraped_leads
(
    il_id integer DEFAULT nextval('v5_instructor_leads_il_id_seq'::regclass) PRIMARY KEY NOT NULL,
    il_url varchar NOT NULL,
    il_company varchar(255),
    il_timestamp timestamp,
    il_email varchar(255),
    il_overview text,
    il_review_count bigint,
    il_rating_score double precision,
    il_course_count integer,
    il_website varchar(255) DEFAULT NULL::character varying,
    il_facebook varchar(255) DEFAULT NULL::character varying,
    il_twitter varchar(255) DEFAULT NULL::character varying,
    il_student_count bigint DEFAULT 0,
    il_udemy_user_id integer DEFAULT 0,
    il_udemy_category varchar(255),
    il_first_name varchar(255),
    il_last_name varchar(255),
    il_youtube varchar(255) DEFAULT NULL::character varying,
    il_linkedin varchar(255) DEFAULT NULL::character varying
);
COMMENT ON COLUMN public.v5_scraped_leads.il_timestamp IS 'When this lead was last updated';