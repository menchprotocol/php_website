
--
-- The single table that runs the MENCH platform
--

create table mench_ledger
(

  -- CORE VARIABLES:
  transaction_id                bigserial primary key, -- Unique, system-generated
  transaction_timestamp         timestamp,             -- GMT -8 Los Angeles Time
  transaction_content           text,                  -- The core content being added to the ledger
  transaction_coins             double precision,      -- Positive value when blogging and negative value when reading
  transaction_order             smallint,              -- Order relative to its siblings
  transaction_metadata          text,                  -- System-generated variables for faster performance

  -- ENTITY REFERENCES:
  transaction_entity_type_id    integer,               -- References one of these players: https://mench.com/play/4593
  transaction_entity_status_id  integer,               -- References one of these players: https://mench.com/play/6186
  transaction_entity_miner_id   integer,               -- References one of these players: https://mench.com/play/4430
  transaction_entity_parent_id  integer,               -- References a player as parent
  transaction_entity_child_id   integer,               -- References a player as child

  -- INTENT REFERENCES:
  transaction_intent_parent_id  integer,               -- References a blog as parent
  transaction_intent_child_id   integer,               -- References a blog as child

  -- OTHER REFERENCES:
  transaction_transaction_id    bigint,                -- References another ledger transaction
  transaction_external_id       bigint                 -- References an ID in an external platform

);