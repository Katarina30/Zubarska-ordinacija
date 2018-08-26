function potvrdiAkciju(pitanje){
	return confirm(pitanje);
}

function zapamtiSelektovano(forma, element, vrednost){
	var selektovanaLista = forma.elements[element]; 
	for(var i=0; i<selektovanaLista.length; i++){
		
		if(selektovanaLista.options[i].value == vrednost)
			selektovanaLista.options[i].selected = true;
	}
}

function createRequest(){
	if(window.XMLHttpRequest){
		// novije verzije IE (od 7 pa na vise), FF, Opera, Safari, GH
		var req = new XMLHttpRequest();
	}
	else{
		var req = new ActiveXObject(Microsoft.XMLHTTP);
	}
	return req;
}

