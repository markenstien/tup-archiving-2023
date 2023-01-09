drop table if exists tasks;
create table tasks(
    id int(10) not null primary key auto_increment,
    task_reference char(50) unique,
    task_name varchar(100),
    task_code varchar(50),
    google_form_link text,
    parent_id int(10) not null comment 'consirederd as class_id',
    status varchar(20),
    description text,
    created_by int(10) not null,
    passing_score smallint,
    created_at timestamp default now()
);


