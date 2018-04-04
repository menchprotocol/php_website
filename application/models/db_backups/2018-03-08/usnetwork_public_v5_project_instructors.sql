CREATE TABLE public.v5_bootcamp_team
(
    ba_id integer DEFAULT nextval('v5_bootcamp_admins_ba_id_seq'::regclass) PRIMARY KEY NOT NULL,
    ba_creator_id integer NOT NULL,
    ba_timestamp timestamp NOT NULL,
    ba_u_id integer NOT NULL,
    ba_status smallint NOT NULL,
    ba_team_display boolean DEFAULT true NOT NULL,
    ba_b_id integer DEFAULT 0 NOT NULL
);
COMMENT ON COLUMN public.v5_bootcamp_team.ba_id IS 'Auto-incremented. The primary ID of the bootcamp access row.';
COMMENT ON COLUMN public.v5_bootcamp_team.ba_creator_id IS 'Maps to v5_users.u_id and indicates the creator user. This is NOT the user who can access the bootcamp, just a record of the creator of the permission.';
COMMENT ON COLUMN public.v5_bootcamp_team.ba_timestamp IS 'The timestamp that this permission was created.';
COMMENT ON COLUMN public.v5_bootcamp_team.ba_u_id IS 'Maps to v5_users.u_id and indicates the user that has been assigned as an instructor to this bootcamp.';
COMMENT ON COLUMN public.v5_bootcamp_team.ba_status IS 'Indicates the level of the instructor that is assigned to this bootcamp. For more details on statuses visit: https://mench.co/console/help/status_bible';
COMMENT ON COLUMN public.v5_bootcamp_team.ba_team_display IS 'Indicates if this instructor should be displayed in the bootcamp landing page.';
COMMENT ON COLUMN public.v5_bootcamp_team.ba_b_id IS 'Maps to v5_bootcamps and indicates the bootcamp that the insturctor assigned in b_u_id can access.';
CREATE INDEX ba_id ON public.v5_bootcamp_team (ba_id);
CREATE INDEX ba_u_id ON public.v5_bootcamp_team (ba_u_id);
CREATE INDEX ba_status ON public.v5_bootcamp_team (ba_status);
CREATE INDEX v5_bootcamp_instructors_ba_b_id_index ON public.v5_bootcamp_team (ba_b_id);