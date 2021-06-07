<section class="ftco-section">
  <!-- contenitore che contiene il form -->
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-5">
        <!-- Errore campo password -->
        <?php if(isset($_SESSION['passwordErr'])):?>
          <!-- Notifica di errore -->
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['passwordErr']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif;?>
        <!-- Errore campo password di conferma -->
        <?php if(isset($_SESSION['confirmPasswordErr'])):?>
          <!-- Notifica di errore -->
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo  $_SESSION['confirmPasswordErr']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif;?>
        <div class="login-wrap p-4 p-md-5">
          <h3 class="text-center mb-4">Cambia password</h3>
          <form action="<?php echo URL; ?>login/modifiyPassword/<?php echo $token; ?>" method="post" class="login-form">
            <div class="form-group">
              <!-- campo email -->
              <input type="password" class="form-control rounded-left" name="password" placeholder="Password">
            </div>
            <div class="form-group d-flex">
                <!-- campo password -->
              <input type="password" class="form-control rounded-left" name="confirmPassword" placeholder="Conferma password">
            </div>
            <div class="form-group">
              <!-- pulsante conferma -->
              <button type="submit" class="form-control btn btn-primary rounded submit px-3">Conferma</button>
            </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Script per la gestione delle notifiche -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/js/alert.js"></script>
