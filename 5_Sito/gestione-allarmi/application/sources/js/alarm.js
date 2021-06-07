
	stateCheck = [];

	//titolo dei vari campi
	titleField = ['Host', "Stato", "Ultimo check", "Durata", "Informazione stato"]

	//url per creare degli allarmi
	url = "http://localhost:8080/gestione-allarmi/allarme/createAlarm";

	//url per cambiare i check
	urlChangeCheck = "http://localhost:8080/gestione-allarmi/allarme/changeCheck"

	//url per caricare gli allarmi
	urlLoad = "http://localhost:8080/gestione-allarmi/allarme/loadAlarm"

	isFirst = true;

	//elemento schermo
	schermo = $("#schermo");

	/**
	 * Permette di cambiare lo stato dei vari check.
	 */
	function changeStateCheck(index, id){
		value = document.getElementById(id).checked;
		$.ajax({
			url: urlChangeCheck,
			type: 'POST',
			data:{"check": value, "index": id}
		});
	  }

	//Permette di intercettare gli eventuali allarmi in modo automatico
	setInterval(
		function(){
			$.ajax({
				url: url,
				type: 'POST',
				data:{status: "critical"},
				success: function(e){
					count = 0;
					$.each(JSON.parse(e), function(index, value){
						//riempio l'array che mantiene lo stato dei check.
						stateCheck[count] = (value==1?true:false);
						++count;
				    });
				}
			});
		},
	1000);


	//Permette di aggiornare la pagina monitor (esterno e desktop)
	setInterval(
		function(){
			$.ajax({
				url: urlLoad,
				type: 'POST',
				success: function(result){
					//opzione mappa
					if(stateCheck[5]){
						//se ci sono degli allarmi
						if(result != null){
							if(isFirst){
								printMap(2);
								isFirst = false;
							}
							printAlarm(1, JSON.parse(result), stateCheck);
						}
						else{
							//stampo la mappa
							if(isFirst){
								printMap(1);
								isFirst = false;
							}
						}
					}
					else{
						if(result){
								printAlarm(2, JSON.parse(result), stateCheck);
						}
						else{
							//elimino tutti gli elementi presenti nello schermo

							schermo.empty();
						}
					}
				}

			});
		},
	1000);


	/**
	 * Permette di stampare la mappa di rete.
	 */
	function printMap(option){
		//se non ci sono allarmi la mappa occupa tutto lo spazio
		if(option == 1){
			schermo.html(
				"<div class='embed-responsive embed-responsive-4by3'>"+
				"<iframe class='embed-responsive-item' src='http://monitor.cpt.local/map.php?host=all' allowfullscreen>"+
				"</iframe></div>"
			);
		}
		else{
			schermo.html(
				"<div class='row'>"+
					"<div id='alarm-display' style='margin-left:5px' class='col'>"+
					"</div>"+
					"<div class='col'>" +
						"<div class='embed-responsive embed-responsive-16by9 mt-3'>"+
						"<iframe class='embed-responsive-item' src='http://monitor.cpt.local/map.php?host=all' allowfullscreen>"+
						"</iframe></div>" +
					"</div>" +
				"</div>"
			)
		}
	}

	/**
	 * Permette di stampare l'allarme.
	 */
	function printAlarm(option, result, state){
		//se è presente anche la mappa.
		if(option == 1){
			$("#alarm-display").html(
				"<table class='mt-3 responsive-table table table-striped table-bordered' style='width:100%;'>"+
				"<thead>"+
				"<tr>"+
				printHeader(state)+
				"</tr>"+
				"<thead>"+
				"<tbody>"+
				"<tr>" +
				printDataAlarm(state, result)+
				"<tr>"+
				"<tbody>"+
				"</table>"
			);

		}
		else{
				schermo.html(
					"<table class='mt-3 responsive-table table table-striped table-bordered' style='width:100%;'>"+
					"<thead>"+
					"<tr>"+
					printHeader(state)+
					"</tr>"+
					"<thead>"+
					"<tbody>"+
					"<tr>" +
					printDataAlarm(state, result)+
					"<tr>"+
					"<tbody>"+
					"</table>"
				);
		}
	}


	/**
	 * Permette di stampare l'header della tabella.
	 */
	function printHeader(state){
		header = "";
		$.each(titleField, function(index, value){
			if(state[index]){
				header+="<th>"+value+"</th>";
			}
		});
		return header;
	}


	/**
	 * Permette di stampare i dati della tabella.
	 */
	function printDataAlarm(state, result){
		data = "";
		count = 0;
		$.each(result, function(index, value){
			//non devo stampare l'indice e il servizio
			if(state[count] && index != "servizio" && index != "id"){
				//se è il campo stato devo mettere il valore "Critical", invece del codice.
				if(index == "stato"){
					data+="<td style='background-color:#EEB4B4;'>Critical</td>";
				}
				else{
					data+="<td style='background-color:#EEB4B4;'>"+value+"</td>";
					++count;
				}
			}
		});
		return data;
	}
