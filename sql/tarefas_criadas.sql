CREATE TABLE `tarefas_criadas` (
	`tarefa_id` INT(11) NOT NULL AUTO_INCREMENT,
	`titulo` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`prioridade` INT(11) NULL DEFAULT NULL,
	`ptc_num` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`criado_por` INT(11) NULL DEFAULT NULL,
	`usuario_tarefa` INT(11) NULL DEFAULT NULL,
	`data_criada` DATETIME NULL DEFAULT current_timestamp(),
	`data_final` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`tarefa_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
