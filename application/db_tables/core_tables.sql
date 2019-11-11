
--
-- The single table that runs MENCH
--

create table mench_ledger
(

  -- REQUIRED:
  transaction_id                bigserial primary key, -- Unique/system-generated ID
  transaction_timestamp         timestamp,             -- Los Angeles time zone
  transaction_coins             double precision,      -- Positive when input/blog and negative value output/read
  transaction_impact            double precision,      -- a number between -1 and 1 indicating relative weight
  transaction_type_id           bigint,                -- References a PLAY of https://mench.com/play/4593
  transaction_status_id         bigint,                -- References a PLAY of https://mench.com/play/6186

  
  -- OPTIONAL:
  transaction_data              text,                  -- Knowledge added to the ledger
  transaction_metadata          text,                  -- Cache variables generated using cron-jobs
  transaction_miner_id          bigint,                -- References a PLAY of https://mench.com/play/4430
  transaction_parent_id         bigint,                -- References a parent PLAY/BLOG
  transaction_child_id          bigint,                -- References a child PLAY/BLOG
  transaction_reference_id      bigint                 -- References an internal/external PLAY/BLOG/object
);
