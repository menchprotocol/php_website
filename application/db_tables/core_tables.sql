
--
-- The single table that runs MENCH
--

create table mench_ledger
(

  -- CONTENT:
  transaction_id                bigserial primary key, -- Unique/system-generated ID
  transaction_timestamp         timestamp,             -- Los Angeles time zone
  transaction_coins             double precision,      -- Positive when input/blog and negative value output/read
  transaction_correlation       double precision,      -- a number between -1 and 1 indicating relative weight
  transaction_data              text,                  -- [ Optional ] The knowledge added to the ledger
  transaction_metadata          text,                  -- [ Optional ] Cache variables generated using cron-jobs
  
  -- CONNECTIONS:
  transaction_type_id           bigint,                -- References one of these players: https://mench.com/play/4593
  transaction_status_id         bigint,                -- References one of these players: https://mench.com/play/6186
  transaction_miner_id          bigint,                -- [ Optional ] References a player: https://mench.com/play/4430
  transaction_parent_id         bigint,                -- [ Optional ] References a parent player/blog
  transaction_child_id          bigint,                -- [ Optional ] References a child player/blog
  transaction_reference_id      bigint                 -- [ Optional ] References an internal/external player/blog/object

);
