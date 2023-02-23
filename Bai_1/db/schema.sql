use dtbase;

drop table if EXISTS users;
create table users(id int PRIMARY key AUTO_INCREMENT, username varchar
(50) not null unique, email varchar
(50) not null unique, password varchar
(50) not null );

insert into users
values(1, "guest", 'guest@gmail.com', "123456"),
    (2, "admin", 'admin@gmail.com', "admin");