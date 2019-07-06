use task_app;
drop table if exists tasks;
create table tasks (
  id int not null auto_increment primary key,
  state tinyint(1) default 0,
  title text
);

insert into tasks (state, title) values
(0, 'task 1'),
(0, 'task 2'),
(0, 'task 3');
