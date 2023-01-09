create table item_comments(
    id int(10) not null PRIMARY KEY AUTO_INCREMENT,
    item_id int(10) not null,
    comment text,
    parent_id int(10),
    user_id int(10),
    created_at datetime DEFAULT null,
    updated_at datetime DEFAULT null ON UPDATE now()
);