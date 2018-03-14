CREATE TABLE public.v5_intent_links
(
    cr_id integer DEFAULT nextval('v5_challenge_relations_id_seq'::regclass) PRIMARY KEY NOT NULL,
    cr_creator_id integer NOT NULL,
    cr_timestamp timestamp NOT NULL,
    cr_status smallint DEFAULT 1 NOT NULL,
    cr_inbound_id integer NOT NULL,
    cr_outbound_id integer NOT NULL,
    cr_outbound_rank integer NOT NULL
);
COMMENT ON COLUMN public.v5_intent_links.cr_status IS 'status_bible()';
COMMENT ON COLUMN public.v5_intent_links.cr_inbound_id IS 'The Inbound challenge ID.';
COMMENT ON COLUMN public.v5_intent_links.cr_outbound_id IS 'The Outbound challenge ID.';
COMMENT ON COLUMN public.v5_intent_links.cr_outbound_rank IS 'The Outbound rank of this challenge.';
CREATE INDEX v5_challenge_relations_id_idx ON public.v5_intent_links (cr_id);
CREATE INDEX v5_challenge_relations_creator_id_idx ON public.v5_intent_links (cr_creator_id);
CREATE INDEX v5_challenge_relations_status_idx ON public.v5_intent_links (cr_status);
CREATE INDEX v5_challenge_relations_in_cid_idx ON public.v5_intent_links (cr_inbound_id);
CREATE INDEX v5_challenge_relations_out_cid_idx ON public.v5_intent_links (cr_outbound_id);
CREATE INDEX v5_challenge_relations_out_rank_idx ON public.v5_intent_links (cr_outbound_rank);