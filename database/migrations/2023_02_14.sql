alter table categories
    add column parent_id int(10);


alter table items
    add column category_id int(10);

alter table categories 
    add column abbr char(10);