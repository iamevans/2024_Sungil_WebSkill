create table notice(
    idx int not null auto_increment,
    msg LONGTEXT not null,
    reg_date datetime not null default CURRENT_TIMESTAMP,
    primary key (idx)
);