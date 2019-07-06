drop database if exists task_app;
create database task_app;
grant all on task_app.* to user@localhost identified by 'dbuser';
