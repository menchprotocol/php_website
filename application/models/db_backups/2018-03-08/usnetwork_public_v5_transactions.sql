CREATE TABLE public.v5_transactions
(
    t_id integer DEFAULT nextval('v5_transactions_t_id_seq'::regclass) PRIMARY KEY NOT NULL,
    t_creator_id integer NOT NULL,
    t_timestamp timestamp NOT NULL,
    t_currency char(3) NOT NULL,
    t_total double precision NOT NULL,
    t_paypal_id varchar(255) NOT NULL,
    t_ru_id integer NOT NULL,
    t_paypal_ipn text,
    t_fees double precision DEFAULT 0 NOT NULL,
    t_payment_type varchar(100),
    t_status smallint DEFAULT 1 NOT NULL,
    t_r_id integer DEFAULT 0 NOT NULL,
    t_u_id integer DEFAULT 0 NOT NULL
);
COMMENT ON COLUMN public.v5_transactions.t_paypal_id IS 'The Transaction ID on paypal';
COMMENT ON COLUMN public.v5_transactions.t_fees IS 'The amount deducted before reaching our account.';
CREATE INDEX v5_transactions_t_timestamp_index ON public.v5_transactions (t_timestamp);
CREATE INDEX v5_transactions_t_ru_id_index ON public.v5_transactions (t_ru_id);
CREATE INDEX v5_transactions_t_status_index ON public.v5_transactions (t_status);
CREATE INDEX v5_transactions_t_r_id_index ON public.v5_transactions (t_r_id);
CREATE INDEX v5_transactions_t_u_id_index ON public.v5_transactions (t_u_id);