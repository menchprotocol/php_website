CREATE TABLE public.v5_users
(
    u_id integer DEFAULT nextval('v5_users_id_seq'::regclass) PRIMARY KEY NOT NULL,
    u_timestamp timestamp,
    u_email varchar(250),
    u_timezone double precision DEFAULT NULL::numeric,
    u_image_url varchar(250),
    u_status smallint DEFAULT 0,
    u_fname varchar(100) NOT NULL,
    u_lname varchar(100) DEFAULT NULL::character varying,
    u_bio text,
    u_fb_id bigint,
    u_url_key varchar(250) NOT NULL,
    u_country_code varchar,
    u_gender varchar,
    u_language varchar(255),
    u_password varchar(100) DEFAULT NULL::character varying,
    u_fb_username varchar(100),
    u_linkedin_username varchar(100),
    u_github_username varchar(100),
    u_current_city varchar(150),
    u_twitter_username varchar(100),
    u_website_url varchar(255),
    u_youtube_username varchar(100),
    u_instagram_username varchar(100),
    u_phone varchar(30),
    u_skype_username varchar(100),
    u_terms_agreement_time timestamp,
    u_calendly_username varchar(100),
    u_paypal_email varchar(250) DEFAULT NULL::character varying,
    u_unsubscribe_fb_id bigint DEFAULT 0 NOT NULL,
    u_cache__fp_id integer,
    u_cache__fp_psid bigint,
    u_fb_notification varchar(20) DEFAULT 'SILENT_PUSH'::character varying NOT NULL,
    u_launch_date timestamp
);
COMMENT ON COLUMN public.v5_users.u_timezone IS 'The timezone of the user when entering the challenge.';
COMMENT ON COLUMN public.v5_users.u_status IS '-1 deleted account / 1 regular user / 2 admin';
COMMENT ON COLUMN public.v5_users.u_terms_agreement_time IS 'Each lead-instructor is asked to agree to Mench terms. This field is the timestamp of when they did that. If NULL it means they have not yet agreed to Mench terms.';
COMMENT ON COLUMN public.v5_users.u_unsubscribe_fb_id IS 'This field holds the original value for u_fb_id for those who decided to unsubscribe from it. Once set, u_fb_id will be set to 0 so we no longer send them automated messages, but we can still contact them directly via Facebook Inbox.';
CREATE UNIQUE INDEX v5_users_u_email_uindex ON public.v5_users (u_email);
CREATE UNIQUE INDEX v5_users_u_fb_id_uindex ON public.v5_users (u_fb_id);
CREATE UNIQUE INDEX v5_users_u_cache__fp_id_u_cache__fp_psid_uindex ON public.v5_users (u_cache__fp_id, u_cache__fp_psid);
CREATE INDEX v5_users_u_timestamp_index ON public.v5_users (u_timestamp);
CREATE INDEX v5_users_u_status_index ON public.v5_users (u_status);
CREATE INDEX v5_users_u_url_key_index ON public.v5_users (u_url_key);
CREATE INDEX v5_users_u_fb_page_id_index ON public.v5_users (u_cache__fp_id);
CREATE INDEX v5_users_u_cache__fp_psid_index ON public.v5_users (u_cache__fp_psid);