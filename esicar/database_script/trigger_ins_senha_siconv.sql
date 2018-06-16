DROP TRIGGER IF EXISTS ins_senha_siconv;

DELIMITER // 
CREATE TRIGGER `ins_senha_siconv` AFTER UPDATE ON usuario 
FOR EACH ROW 
BEGIN 
	DECLARE num_linhas INT;
    DECLARE municipio INT;
    DECLARE cnpj VARCHAR(14);
	
    IF new.id_nivel = 4 AND new.login_siconv <> '' AND new.senha_siconv <> '' THEN
		SET num_linhas = (SELECT COUNT(*) FROM guarda_senha WHERE login = new.login_siconv);
        SET cnpj = (SELECT cnpj_siconv.cnpj FROM cnpj_siconv JOIN usuario_cnpj ON id_cnpj = id_cnpj_siconv WHERE id_usuario = new.id_usuario LIMIT 1);
        SET municipio = (SELECT cnpj_siconv.id_cidade FROM cnpj_siconv JOIN usuario_cnpj ON id_cnpj = id_cnpj_siconv WHERE id_usuario = new.id_usuario LIMIT 1);
		
		IF num_linhas > 0 THEN
			UPDATE guarda_senha SET senha = new.senha_siconv WHERE login = new.login_siconv;
		ELSE
			INSERT INTO guarda_senha VALUES (new.login_siconv, new.senha_siconv, municipio, cnpj);
		END IF;
    END IF;
END // 
DELIMITER ;