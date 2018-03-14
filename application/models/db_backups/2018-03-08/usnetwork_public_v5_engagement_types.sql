CREATE TABLE public.v5_engagement_types
(
    a_id integer DEFAULT nextval('v5_user_actions_a_id_seq'::regclass) PRIMARY KEY NOT NULL,
    a_name varchar(100) NOT NULL,
    a_desc text
);
CREATE INDEX a_id ON public.v5_engagement_types (a_id);