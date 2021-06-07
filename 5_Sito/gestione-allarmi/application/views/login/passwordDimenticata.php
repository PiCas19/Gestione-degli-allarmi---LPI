<section class="ftco-section">
  <!-- contenitore che contiene il form -->
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-5">
        <!-- Errore campo password -->
        <?php if(isset($_SESSION['emailErr'])):?>
          <!-- Notifica di errore -->
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['emailErr']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif;?>
        <div class="login-wrap p-4 p-md-5">
          <h3 class="text-center mb-4">Password dimeticata</h3>
          <form action="<?php echo URL; ?>login/sendEmailToChangePassword" method="post" class="login-form">
            <div class="form-group">
              <!-- campo email -->
              <input type="email" class="form-control rounded-left" name="email" placeholder="Email">
            </div>
            <div class="form-group">
              <!-- pulsante richiedi nuova password -->
              <button name="send" type="submit" class="text-white btn bg-success rounded submit px-3">Richiedi nuova password</button>
              <!-- pulsante esci -->
              <button name="exit" type="submit" class="text-white btn bg-secondary rounded submit px-3">Esci</button>
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
