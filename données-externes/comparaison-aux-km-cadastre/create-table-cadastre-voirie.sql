DROP TABLE IF EXISTS cadastre CASCADE;
CREATE TABLE cadastre(
    refinsee varchar,
    name varchar,
    wtype varchar,
    km float)
;
create index index_refinsee on cadastre (refinsee,wtype);

