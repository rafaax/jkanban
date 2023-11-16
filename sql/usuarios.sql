CREATE TABLE `usuarios` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`login` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`senha` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`email` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`permissoes` INT(11) NULL DEFAULT NULL,
	`auth_token` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
