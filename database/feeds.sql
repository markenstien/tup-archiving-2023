DROP TABLE IF exists feeds;
create table feeds(
    id int(10) not null PRIMARY KEY AUTO_INCREMENT,
    parent_id int(10),
    parent_key varchar(50),
    title varchar(50),
    content text,
    owner_id int(10),
    tags text,
    category varchar(50),
    type enum('announcement','feed'),
    created_at timestamp DEFAULT now()
);


create table feed_comments(
    id int(10) not null PRIMARY KEY AUTO_INCREMENT,
    feed_id int(10),
    user_id int(10),
    thread_id int(10),
    replied_to int(10),
    comment text,
    created_at timestamp DEFAULT now()
);


create table feed_attributes(
    id int(10) not null PRIMARY KEY AUTO_INCREMENT,
    parent_id int(10),
    parent_key varchar(50),
    type enum('likes','dislikes'),
    created_at timestamp DEFAULT now()
);