function calculoSistemaRelatorio(nu_simples, nu_media, nu_complexa) {
	// Calcula
	total_simples = (nu_simples * 1) +  (nu_media * 2) + (nu_complexa * 3);
	
	return total_simples;
}

function calculoBasicaAssociativa(valor) {
	if (valor < 5) {
		totalValor = valor * 2;
	} else if ( (valor > 4) && (valor < 9) ) {
		totalValor = valor * 5;
	} else {
		totalValor = valor * 7;
	}
	
	return totalValor;
}

/*
PMD = (nu_pagina_simples * 1) + (nu_pagina_media * 2) + (nu_pagina_complexa * 3) + (ni_elementos_simples * 0.1) + (ni_elementos_media * 0.5) + (ni_elementos_complexa * 1)
QTHS = PMD * 20
QTHS é o total de horas unitario de metrica web
*/
function calculoSitio(nu_pagina_simples, nu_pagina_media, nu_pagina_complexa, ni_elementos_simples, ni_elementos_media, ni_elementos_complexa) {
	PMD  = (nu_pagina_simples * 1) 
		   + (nu_pagina_media * 2) 
		   + (nu_pagina_complexa * 3) 
		   + (ni_elementos_simples * 0.1) 
		   + (ni_elementos_media * 0.5) 
		   + (ni_elementos_complexa * 1);
	
	return PMD; 
}