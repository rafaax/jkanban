CREATE TABLE `tarefas_todo` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`tarefa_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
