CREATE TABLE `users` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `created_at` datetime NOT NULL,
                         `email` varchar(320) NOT NULL,
                         `name` varchar(250) NOT NULL,
                         `password` varchar(100) NOT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `projects` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `name` varchar(255) NOT NULL,
                            `user_id` int(11) NOT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB;


CREATE TABLE `tasks` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `created_at` datetime NOT NULL,
                         `status` varchar(20) NOT NULL,
                         `header` varchar(50) NOT NULL,
                         `description` varchar(350) NOT NULL,
                         `file` varchar(350) NOT NULL,
                         `deadline` date NOT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB