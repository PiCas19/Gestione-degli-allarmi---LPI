/* popup di eliminazione */
var deleteModal = document.getElementById('deleteModal');

/* paragrafo che viene scritto nel popup */
var description = document.getElementById('message');


/* quando l'utente clicca sul pulsante "Elimina" compare il popup di conferma */
deleteModal.addEventListener('show.bs.modal', function (event) {
  /* quando l'utente clicca sul pulsante "Elimina" */
  var button = event.relatedTarget;

  /* setto il messaggio del popup */
  description.innerHTML = button.getAttribute('data-information');

  /* ottengo l'URL  */
  var url = button.getAttribute('data-url');

  /* l'url deve essere definito nell'attributo data-url del pulsante "Elimina" */
  if (typeof url !== 'undefined') {
  	/* Aggiungo la proprietà href al pulsante "Sì, conferma". */
  	/* Quando verrà premuto il pulsante verrà richiamato
    /* il metodo deleteInformazione del controller Home. */
    $("#confirm").attr("href", url);
  }
});
