ALTER TABLE `proceso` ADD `empresa` VARCHAR(200) NULL AFTER `estado`, ADD `rut` VARCHAR(200) NULL AFTER `empresa`, ADD `adherente` VARCHAR(200) NULL AFTER `rut`, ADD `act_economica` VARCHAR(200) NULL AFTER `adherente`, ADD `act_economica_cod` VARCHAR(200) NULL AFTER `act_economica`, ADD `centro_trabajo` VARCHAR(200) NULL AFTER `act_economica_cod`, ADD `comuna` VARCHAR(200) NULL AFTER `centro_trabajo`, ADD `rep_legal` VARCHAR(200) NULL AFTER `comuna`, ADD `contacto_empresa` VARCHAR(200) NULL AFTER `rep_legal`, ADD `contacto_empresa_telefono` VARCHAR(200) NULL AFTER `contacto_empresa`;
ALTER TABLE `proceso` ADD `avance_obras` VARCHAR(200) NULL AFTER `contacto_empresa_telefono`, ADD `t1_horas` VARCHAR(200) NULL AFTER `avance_obras`, ADD `t1_dias` VARCHAR(200) NULL AFTER `t1_horas`, ADD `t1_jornada` VARCHAR(200) NULL AFTER `t1_dias`, ADD `t2_horas` VARCHAR(200) NULL AFTER `t1_jornada`, ADD `t2_dias` VARCHAR(200) NULL AFTER `t2_horas`, ADD `t2_jornada` VARCHAR(200) NULL AFTER `t2_dias`, ADD `t3_horas` VARCHAR(200) NULL AFTER `t2_jornada`, ADD `t3_dias` VARCHAR(200) NULL AFTER `t3_horas`, ADD `t3_jornada` VARCHAR(200) NULL AFTER `t3_dias`, ADD `desc_sistema_turnos` VARCHAR(200) NULL AFTER `t3_jornada`, ADD `visita_1` VARCHAR(200) NULL AFTER `desc_sistema_turnos`, ADD `visita_2` VARCHAR(200) NULL AFTER `visita_1`, ADD `visita_3` VARCHAR(200) NULL AFTER `visita_2`, ADD `visita_4` VARCHAR(200) NULL AFTER `visita_3`, ADD `profesional_medicion` VARCHAR(200) NULL AFTER `visita_4`;