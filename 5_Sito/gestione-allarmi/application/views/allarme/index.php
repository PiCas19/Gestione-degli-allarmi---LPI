

<div class="container mt-5" style="margin-bottom:80px;">
  <!-- Check campo host -->
  <div class="form-check float-start mx-2">
    <?php if($row['isHost']==1): ?>
    <input class="form-check-input" type="checkbox" id="isHost" value="isHost" onclick="changeStateCheck(0, this.value)" checked>
    <?php else: ?>
    <input class="form-check-input" type="checkbox" id="isHost" value="isHost" onclick="changeStateCheck(0, this.value)">
    <?php endif; ?>
    <label class="form-check-label" for="checkedHost">
      Host
    </label>
  </div>
  <!-- Check campo stato -->
  <div class="form-check float-start mx-2">
    <?php if($row['isStatus']==1): ?>
    <input class="form-check-input" type="checkbox" id="isStatus" value="isStatus" onclick="changeStateCheck(1, this.value)" checked>
    <?php else: ?>
    <input class="form-check-input" type="checkbox" id="isStatus" value="isStatus" onclick="changeStateCheck(1, this.value)">
    <?php endif;?>
    <label class="form-check-label" for="checkedStatus">
      Stato
    </label>
  </div>
  <!-- Check campo ultimo check -->
  <div class="form-check float-start mx-2">
    <?php if($row['isLastCheck']==1): ?>
    <input class="form-check-input" type="checkbox" id="isLastCheck" value="isLastCheck" onclick="changeStateCheck(2, this.value)" checked>
    <?php else: ?>
    <input class="form-check-input" type="checkbox" id="isLastCheck" value="isLastCheck" onclick="changeStateCheck(2, this.value)">
    <?php endif;?>
    <label class="form-check-label" for="checkedLastCheck">
      Ultimo check
    </label>
  </div>
  <!-- Check campo durata -->
  <div class="form-check float-start mx-2">
    <?php if($row['isDuration']==1): ?>
    <input class="form-check-input" type="checkbox" id="isDuration" value="isDuration" onclick="changeStateCheck(3, this.value)" checked>
    <?php else: ?>
    <input class="form-check-input" type="checkbox" id="isDuration" value="isDuration" onclick="changeStateCheck(3, this.value)">
    <?php endif;?>
    <label class="form-check-label" for="checkedDuration">
      Durata
    </label>
  </div>
  <!-- Check campo informazione stato -->
  <div class="form-check float-start mx-2">
    <?php if($row['isStatusInformation']==1): ?>
    <input class="form-check-input" type="checkbox" id="isStatusInformation" value="isStatusInformation" onclick="changeStateCheck(4, this.value)" checked>
    <?php else: ?>
    <input class="form-check-input" type="checkbox" id="isStatusInformation" value="isStatusInformation" onclick="changeStateCheck(4, this.value)">
    <?php endif;?>
    <label class="form-check-label" for="checkedInformationStatus">
      Informazione stato
    </label>
  </div>
  <!-- Check mappa di rete -->
  <div class="form-check float-start mx-2">
    <?php if($row['isMap']==1): ?>
    <input class="form-check-input" type="checkbox" id="isMap" value="isMap" onclick="changeStateCheck(5, this.value)" checked>
    <?php else: ?>
    <input class="form-check-input" type="checkbox" id="isMap" value="isMap" onclick="changeStateCheck(5, this.value)">
    <?php endif;?>
    <label class="form-check-label" for="checkedInformationStatus">
      Mappa di rete
    </label>
  </div>
  <br>
  <br>
  <!-- Tabella degli servizi -->
 <table id="allarmi-table" class="responsive-table table table-striped table-bordered" style="width:100%;">
   <!-- Header della tabella -->
   <thead>
     <tr>
       <th>Host</th>
       <th>Servizio</td>
       <th>Stato</th>
       <th>Ultimo check</th>
       <th>Durata</th>
       <th>Informazione stato</th>
     </tr>
   </thead>
   <!-- Valori nella tabella -->
   <tbody>
     <?php foreach ($stats as $value): ?>
       <tr>
         <td><?php echo $value['hostname']; ?></td>
         <td><?php echo $value['description']; ?></td>
         <?php if( $value['status'] == 2): ?>
         <td class="bg-success">Ok</td>
         <?php elseif( $value['status'] == 4): ?>
         <td  class="bg-warning">Warning</td>
         <?php elseif( $value['status'] == 8): ?>
         <td>Unknown</td>
         <?php else: ?>
         <td class="bg-danger">Critical</td>
         <?php endif; ?>
         <td id="last_check"><?php echo $value['last_check'];?></td>
         <td id="duration"><?php echo $value['duration'];?></td>
         <td><?php echo $value['status_information'];?></td>
       </tr>
      <?php endforeach; ?>
   </tbody>
 </table>

</diV>

<!-- Libreria DataTables -->
<script  type="text/javascript" src="<?php echo URL; ?>application/sources/js/table.js"></script>
<!-- JavaScript allarme -->
<script  type="text/javascript" src="<?php echo URL; ?>application/sources/js/alarm.js"></script>
