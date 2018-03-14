CREATE TABLE public.v5_messages
(
    i_id integer DEFAULT nextval('v5_challenge_content_id_seq'::regclass) PRIMARY KEY NOT NULL,
    i_creator_id integer NOT NULL,
    i_timestamp timestamp NOT NULL,
    i_c_id integer NOT NULL,
    i_message text NOT NULL,
    i_status smallint DEFAULT 1 NOT NULL,
    i_rank integer DEFAULT 1 NOT NULL,
    i_media_type varchar(20) DEFAULT NULL::character varying,
    i_url text
);
COMMENT ON COLUMN public.v5_messages.i_message IS 'What will be sent to the user.';
COMMENT ON COLUMN public.v5_messages.i_status IS 'status_bible()';
CREATE INDEX v5_challenge_content_id_idx ON public.v5_messages (i_id);
CREATE INDEX v5_challenge_content_creator_id_idx ON public.v5_messages (i_creator_id);
CREATE INDEX v5_challenge_content_challenge_id_idx ON public.v5_messages (i_c_id);
CREATE INDEX v5_challenge_content_status_idx ON public.v5_messages (i_status);
CREATE INDEX v5_messages_i_rank_index ON public.v5_messages (i_rank);
CREATE INDEX v5_messages_i_media_type_index ON public.v5_messages (i_media_type);