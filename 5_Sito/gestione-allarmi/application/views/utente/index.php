<div class="container mt-5" style="margin-bottom:80px;">
  <!-- Puoi creare un utente se sei solo amministratore -->
  <?php if($_SESSION['tipo'] == 'amministratore'): ?>
  <a class="btn btn-success justify-content-center text-white mb-3" href="<?php echo URL; ?>utente/viewAddUser"><i class="fa fa-plus"></i>&nbsp;Aggiungi</a>
  <?php endif; ?>
  <!-- Tabella degli utenti -->
  <table id="user-table" class="responsive-table table table-striped table-bordered" style="width:100%;">
    <!-- Header della tabella -->
    <thead>
      <tr>
        <th>Id</th>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Elimina</th>
      </tr>
    </thead>
    <!-- Contenuto della tabella -->
    <tbody>
      <!-- Stampo i dati degli utenti -->
      <?php while($row = $utenti->fetch(PDO::FETCH_ASSOC)): ?>
      <?php
       //url per eliminare un utente
        $url = URL."utente/deleteUserById/". $row['id'];
        //descrizione del popup
        $description = "Sei sicuro di eliminare l'utente  <b>".$row['nome'].
        " ".$row['cognome']."</b>";
       ?>
      <tr>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['nome'] ?></td>
        <td><?php echo $row['cognome'] ?></td>
        <td><?php echo $row['email'] ?></td>
        <?php if($row['email'] == $_SESSION['email'] || $_SESSION['tipo'] == 'limitato'): ?>
          <td><?php echo $row['tipo'] ?></td>
          <td><div style="margin:36px;"></div></td>
        <?php else: ?>
          <td>
             <select class='browser-default custom-select' onchange='changeTypeUser(<?php echo $row['id']; ?>, this.value)'>
               <?php if($row['tipo'] == "amministratore"): ?>
               <option value='amministratore' selected>amministratore</option>
               <option value='limitato'>limitato</option>
               <?php else: ?>
               <option value='amministratore'>amministratore</option>
               <option value='limitato' selected>limitato</option>
             <?php endif; ?>
             </select>
          </td>
          <td><a class="btn btn-danger text-white d-flex justify-content-center" data-url="<?php echo $url;?>"
            data-information="<?php echo $description; ?>" data-bs-toggle="modal" data-bs-target="#deleteModal">Elimina</a></td>
          <?php endif; ?>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<!-- Modal per confermare l'eliminazione di un informazione -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- il popup si troverà al centro della pagina -->
    <div class="modal-dialog modal-dialog-centered">
      <!-- contenuto del popup -->
      <div class="modal-content">
        <!-- intestazione del popup -->
        <div class="modal-header btn-danger text-white">
          <h5 class="modal-title text-white" id="exampleModalLabel">Conferma</h5>
          <!-- icona x per chiudere il popup -->
          <button type="button text-white" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- corpo del popup -->
        <div class="modal-body">
          <!-- messaggio popup -->
          <p id="message"></p>
        </div>
        <!-- piè di pagina popup -->
        <div class="modal-footer">
          <!-- pulsante per chiudere il popup -->
          <button type="button" class="btn border-danger text-danger"  data-bs-dismiss="modal">Annulla</button>
          <!-- pulsante per confermare l'eliminazione -->
          <a class='btn btn-danger' id="confirm">Elimina</a>
        </div>
      </div>
    </div>
  </div>
<!-- JavaScript che permette di gestire il popup -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/js/popup.js"></script>
<!-- Libreria DataTables -->
<script  type="text/javascript" src="<?php echo URL; ?>application/sources/js/table.js"></script>
<!-- JavaScript per gestire i permessi degli utentei -->
<script  type="text/javascript" src="<?php echo URL; ?>application/sources/js/user.js"></script>
<!-- Libreria JavaScript bundle -->
<script  type="text/javascript" src="<?php echo URL; ?>application/sources/bootstrap-5.0.0-dist/js/bootstrap.bundle.js"></script>
