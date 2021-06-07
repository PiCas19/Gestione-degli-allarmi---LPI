$(document).ready(function() {
   $('#user-table').DataTable({
     "language": {
			    "url": "http://localhost:8080/gestione-allarmi/application/sources/DataTables/language/italian.json"
		  }
   });

   $('#allarmi-table').DataTable({
     "language": {
			    "url": "http://localhost:8080/gestione-allarmi/application/sources/DataTables/language/italian.json"
		  }
   });
});
