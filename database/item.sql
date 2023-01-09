drop table if exists items;
create table items(
    id int(10) not null PRIMARY KEY AUTO_INCREMENT,
    reference varchar(100),
    title varchar(150),
    brief text,
    description text,
    genre varchar(100),
    year char(5),
    tags varchar(100),
    authors text,
    publishers text,
    is_viewable boolean DEFAULT true,
    uploader_id int(10),
    approver_id int(10),
    created_at timestamp DEFAULT now(),
    updated_at timestamp DEFAULT now()
);