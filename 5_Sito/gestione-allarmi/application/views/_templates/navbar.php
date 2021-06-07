<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <!-- Icona della navbar -->
    <a class="navbar-brand" href="<?php echo URL; ?>utente/viewAccount">Account</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <!-- Link pagina di gestione degli utenti -->
          <a class="nav-link active" href="<?php echo URL; ?>utente">Gestione utenti</a>
        </li>
        <?php if($_SESSION['tipo'] == 'amministratore'): ?>
        <li class="nav-item">
          <!-- Link pagina di amministrazione -->
          <a class="nav-link active" href="<?php echo  URL;?>allarme">Gestione allarmi</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <!-- Link pagina visualizzazione allarmi -->
          <a class="nav-link active" href="<?php echo URL; ?>allarme/display">Visualizzazione allarmi</a>
        </li>
        <li class="nav-item">
          <!-- Link per potere uscire dall'applicativo web -->
          <a class="nav-link active" href="<?php echo URL; ?>utente/logout">Esci</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
