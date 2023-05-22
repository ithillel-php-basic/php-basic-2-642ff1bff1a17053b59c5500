CREATE TABLE `users`
(
    `id`         int(20)      NOT NULL AUTO_INCREMENT,
    `created_at` datetime     NOT NULL,
    `email`      varchar(320) NOT NULL,
    `name`       varchar(250) NOT NULL,
    `password`   varchar(100) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `email`
    UNIQUE (email)
) ENGINE=InnoDB;

CREATE TABLE `projects`
(
    `id`         int(20)      NOT NULL AUTO_INCREMENT,
    `name`       varchar(250) NOT NULL,
    `user_id`    int(20)      NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `user_id`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `tasks`
(
    `id`         int(20)      NOT NULL AUTO_INCREMENT,
    `created_at` datetime     NOT NULL,
    `status`     varchar(20)  NOT NULL,
    `header`     varchar(50)  NOT NULL,
    `description`varchar(350) NOT NULL,
    `file`       varchar(350) NOT NULL,
    `deadline`   date         NOT NULL,
    `projects_id`int(20)      NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `projects_id`
        FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE=InnoDB;

