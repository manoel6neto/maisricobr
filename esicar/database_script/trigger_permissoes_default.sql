DROP TRIGGER IF EXISTS ins_perm_default;

DELIMITER // 
CREATE TRIGGER `ins_perm_default` AFTER INSERT ON usuario 
FOR EACH ROW 
BEGIN 
	IF new.id_nivel = '1' THEN
		INSERT INTO permissoes_usuario VALUE (null, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, new.id_usuario, 1, 1, 1);
	ELSEIF new.id_nivel = '2' THEN
		INSERT INTO permissoes_usuario VALUE (null, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, new.id_usuario, 1, 1, 1);
	ELSEIF new.id_nivel = '3' THEN
		INSERT INTO permissoes_usuario VALUE (null, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, new.id_usuario, 1, 1, 1);
	ELSEIF new.id_nivel = '4' THEN
		INSERT INTO permissoes_usuario VALUE (null, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, new.id_usuario, 1, 1, 1);
	ELSEIF new.id_nivel = '5' THEN
		INSERT INTO permissoes_usuario VALUE (null, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, new.id_usuario, 1, 1, 1);
	ELSEIF new.id_nivel = '6' THEN
		INSERT INTO permissoes_usuario VALUE (null, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, new.id_usuario, 1, 1, 1);
	ELSEIF new.id_nivel = '7' THEN
		INSERT INTO permissoes_usuario VALUE (null, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, new.id_usuario, 1, 1, 1);
	ELSEIF new.id_nivel = '8' THEN
		INSERT INTO permissoes_usuario VALUE (null, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, new.id_usuario, 1, 1, 1);
	ELSEIF new.id_nivel = '9' THEN
		INSERT INTO permissoes_usuario VALUE (null, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, new.id_usuario, 1, 1, 0);
	END IF;
END // 
DELIMITER ;