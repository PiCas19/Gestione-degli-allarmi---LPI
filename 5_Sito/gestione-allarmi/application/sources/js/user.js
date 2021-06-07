/**
      * Permette di modificare il tipo di utente.
      * @param id identificativo dell'utente.
      * @param value Valore del menu a tebndina (permessi)
      */
      function changeTypeUser(id, value){
        //url
        url = "http://localhost:8080/gestione-allarmi/utente/changeType"
        $.ajax({
          //metodo che permette di cambiare il tipo di utente (amministratore o limitato).
          url: url,
          data: {id: id, value:value},
          //utilizzo il metodo POST
          type: 'post'
        });
      }
