
--
-- Last Updated: Aug 20 2019
--
-- Mench's database consistent of 3 tables:
--
-- 1] Intents
-- 2] Entities
-- 3] Links
--


create table table_intents
(
  in_id                           serial       not null constraint table_intents_pkey primary key,
  in_status_entity_id             integer      not null,
  in_outcome                      varchar(255) not null,
  in_verb_entity_id               integer      not null,
  in_subtype_entity_id            integer      not null,
  in_scope_entity_id              integer      not null,
  in_completion_seconds           smallint     not null,
  in_metadata                     text
);


create table table_entities
(
  en_id                           serial       not null constraint table_entities_pkey primary key,
  en_status_entity_id             integer      not null,
  en_name                         varchar(255) not null,
  en_trust_score                  integer      not null,
  en_icon                         text,
  en_metadata                     text
);


create table table_links
(
  ln_id                           bigserial    not null constraint table_links_pkey primary key,
  ln_status_entity_id             integer      not null,
  ln_type_entity_id               integer      not null,
  ln_creator_entity_id            integer      not null,
  ln_parent_entity_id             integer      not null,
  ln_child_entity_id              integer      not null,
  ln_parent_intent_id             integer      not null,
  ln_child_intent_id              integer      not null,
  ln_parent_link_id               bigint       not null,
  ln_external_id                  bigint       not null,
  ln_order                        smallint     not null,
  ln_timestamp                    timestamp(4) not null,
  ln_content                      text,
  ln_metadata                     text
);






-- Intents
alter table table_intents
  owner to us;

create unique index table_intents_in_id_uindex
  on table_intents (in_id);

create index table_intents_in_verb_entity_id_index
  on table_intents (in_verb_entity_id);

create index table_intents_in_subtype_entity_id_index
  on table_intents (in_subtype_entity_id);

create index table_intents_in_completion_seconds_index
  on table_intents (in_completion_seconds);

create index table_intents_in_status_entity_id_index
  on table_intents (in_status_entity_id);

create index table_intents_in_subscription_entity_id_index
  on table_intents (in_scope_entity_id);


-- Entities
alter table table_entities
  owner to us;

create unique index table_entities_en_id_uindex
  on table_entities (en_id);

create index table_entities_en_trust_score_index
  on table_entities (en_trust_score);

create index table_entities_en_status_entity_id_index
  on table_entities (en_status_entity_id);



-- Links
alter table table_links
  owner to us;

create unique index table_ledger_tr_id_uindex
  on table_links (ln_id);

create index table_ledger_tr_timestamp_index
  on table_links (ln_timestamp);

create index table_ledger_tr_en_creator_id_index
  on table_links (ln_creator_entity_id);

create index table_ledger_tr_en_type_id_index
  on table_links (ln_type_entity_id);

create index table_ledger_tr_en_parent_id_index
  on table_links (ln_parent_entity_id);

create index table_ledger_tr_en_child_id_index
  on table_links (ln_child_entity_id);

create index table_ledger_tr_in_parent_id_index
  on table_links (ln_parent_intent_id);

create index table_ledger_tr_in_child_id_index
  on table_links (ln_child_intent_id);

create index table_ledger_tr_tr_parent_id_index
  on table_links (ln_parent_link_id);

create index table_ledger_tr_order_index
  on table_links (ln_order);

create index table_links_ln_status_entity_id_index
  on table_links (ln_status_entity_id);

create index table_links_ln_index_index
  on table_links (ln_external_id);


