<section class="ftco-section">
  <!-- contenitore che contiene il form -->
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-5">

        <!-- Errore campo nome -->
        <?php if(isset($_SESSION['nameErr'])):?>
          <!-- Notifica di errore -->
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['nameErr']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif;?>

        <!-- Errore campo cognome -->
        <?php if(isset($_SESSION['surnameErr'])):?>
          <!-- Notifica di errore -->
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['surnameErr']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif;?>

        <!-- Errore campo email -->
        <?php if(isset($_SESSION['emailErr'])):?>
          <!-- Notifica di errore -->
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['emailErr']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif;?>

        <!-- Errore campo email -->
        <?php if(isset($_SESSION['typeErr'])):?>
          <!-- Notifica di errore -->
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['typeErr']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif;?>

        <div class="login-wrap p-4 p-md-5">
          <h3 class="text-center mb-4">Crea Utente</h3>
          <form action="<?php echo URL; ?>utente/checkAddUsers" method="post" class="login-form">
            <div class="form-group">
              <!-- campo nome -->
              <input type="text" name="surname" class="form-control rounded-left" placeholder="Nome">

            </div>
            <div class="form-group d-flex">
              <!-- campo cognome -->
              <input type="text" name="lastname" class="form-control rounded-left" placeholder="Cognome">
            </div>
            <div class="form-group d-flex">
                <!-- campo email -->
              <input type="email" name="email" class="form-control rounded-left" placeholder="Email">
            </div>
            <div class="form-group d-flex">
              <!-- Campop permessi -->
              <select class="form-select"  id="type" name="type">
                <option value="amministratore">Amministratore</option>
                  <option value="limitato">Limitato</option>
              </select>
            </div>
            <div class="form-group">
              <!-- pulsante creazione utente -->
              <button name="create" type="submit" class="text-white btn bg-success rounded submit px-3">Crea</button>
              <!-- pulsante esci -->
              <button name="exit" type="submit" class="text-white btn bg-secondary rounded submit px-3">Esci</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Script per la gestione delle notifiche -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/js/alert.js"></script>
