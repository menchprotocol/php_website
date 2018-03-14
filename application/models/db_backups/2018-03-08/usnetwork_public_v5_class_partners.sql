CREATE TABLE public.v5_class_partners
(
    p_id integer DEFAULT nextval('v5_class_partners_p_id_seq'::regclass) PRIMARY KEY NOT NULL,
    p_timestamp timestamp NOT NULL,
    p_r_id integer NOT NULL,
    p_milestone_c_id integer NOT NULL,
    p_milestone_rank integer NOT NULL,
    p_task_cache text,
    p_u_id_1 integer NOT NULL,
    p_u_id_2 integer NOT NULL,
    p_did_complete boolean DEFAULT false NOT NULL
);
COMMENT ON COLUMN public.v5_class_partners.p_task_cache IS 'A cache copy of all the partner task IDs of this milestone that caused this partnership to start';
COMMENT ON COLUMN public.v5_class_partners.p_did_complete IS 'Turns to True when both partners have completed ALL the partner tasks of this given milestone.';
CREATE UNIQUE INDEX v5_class_partners_p_id_uindex ON public.v5_class_partners (p_id);
CREATE INDEX v5_class_partners_p_r_id_index ON public.v5_class_partners (p_r_id);
CREATE INDEX v5_class_partners_p_c_id_index ON public.v5_class_partners (p_milestone_c_id);
CREATE INDEX v5_class_partners_p_u_id_1_index ON public.v5_class_partners (p_u_id_1);
CREATE INDEX v5_class_partners_p_u_id_2_index ON public.v5_class_partners (p_u_id_2);