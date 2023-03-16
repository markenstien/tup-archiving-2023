drop table if exists catalog_logs;
create table catalog_logs(
	id int(10) not null primary key auto_increment,
	user_id int(10),
	catalog_id varchar(50),
	log_message text,
	created_at timestamp default now()
);


create table catalog_archived(
	id int(10) not null primary key auto_increment,
	user_id int(10),
	catalog_id int(10),
	catalog_values text,
	log_message text,
	created_at timestamp default now()
);