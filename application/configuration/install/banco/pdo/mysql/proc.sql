/*
   Essa proc deve ser transformada para MYSQL para funcionar o processo de Rotina
*/
DELIMITER //
CREATE FUNCTION insere_rotina() RETURNS integer
   
begin

 declare registro record;
    for registro into
        select * from 
		(select
		    rp.*,
		    rd.tx_hora_inicio_rotina
		 from
		    a_rotina_profissional as rp
		 join
		    b_rotina as rd 
		 on
		    rp.cd_rotina = rd.cd_rotina
		    and rd.st_periodicidade_rotina = 'D'
		    and st_inativa_rotina_profissional is null
		)as rd1
	union 
		(select
		    rp.*,
		    rs.tx_hora_inicio_rotina
		 from
		    a_rotina_profissional as rp
		 join
		    b_rotina as rs 
		 on
		    rp.cd_rotina = rs.cd_rotina
		    and (rs.st_periodicidade_rotina = 'S' and cast(to_char(now(),'D') as int) = cast(rs.ni_dia_semana_rotina as int))
		    and st_rotina_inativa is null
		 where
		    rp.st_inativa_rotina_profissional is null    
	   
		) 
	union 
		(select
		    rp.*,
		    rm.tx_hora_inicio_rotina
		from
		    a_rotina_profissional as rp
		join
		    b_rotina as rm 
		on
		    rp.cd_rotina = rm.cd_rotina
		    and (rm.st_periodicidade_rotina = 'M' and cast(to_char(now(),'DD') as int) = rm.ni_dia_mes_rotina) 
		    and st_rotina_inativa is null
		where
		    rp.st_inativa_rotina_profissional is null    
	       )
    loop
        INSERT INTO
            s_execucao_rotina
            (
                dt_execucao_rotina,
                cd_profissional,
                cd_objeto,
                cd_rotina,
                tx_hora_execucao_rotina,
                st_fechamento_execucao_rotina,
                dt_just_execucao_rotina,
                tx_just_execucao_rotina,
                st_historico,
                id
            )
        VALUES (
                current_date,
                registro.cd_profissional,
                registro.cd_objeto,
                registro.cd_rotina,
                registro.tx_hora_inicio_rotina,
                    null,
                    null,
                    null,
                    null,
                    registro.id
            );
    end loop;
    return 0;
end;
//
