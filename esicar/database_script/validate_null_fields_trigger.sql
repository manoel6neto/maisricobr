delimiter $$
create trigger nullify_blanks_ins_proposta before insert on `proposta`
for each row begin
    if new.repasse_especifico = '' then
        set new.repasse_especifico = 0.00;
    end if;
end;
$$
create trigger nullify_blanks_upd_proposta before update on `proposta`
for each row begin
    if new.repasse_especifico = '' then
        set new.repasse_especifico = 0.00;
    end if;
end;
$$  
delimiter ;
