create table keywords(
    id int(10) not null PRIMARY KEY AUTO_INCREMENT,
    category varchar(100),
    value varchar(100),
    created_at timestamp DEFAULT now()
);



CREATE VIEW v_keyword_rank as SELECT count(id) as total , category, value 
    FROM keywords
    GROUP BY category,value;