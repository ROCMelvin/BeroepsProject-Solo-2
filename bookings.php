<?php

include 'components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
  
}

if(isset($_POST['delete'])){

   $delete_id = $_POST['delete_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_delete = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
   $verify_delete->execute([$delete_id]);

   if($verify_delete->rowCount() > 0){
      $delete_bookings = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
      $delete_bookings->execute([$delete_id]);
      $success_msg[] = 'Boeking verwijderd!';
   }else{
      $warning_msg[] = 'Boeking al verwijderd!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Boekingen</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- eigen css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<!-- header sectie start  -->
<?php include 'components/admin_header.php'; ?>
<!-- header sectie eind -->

<!-- boekingen sectie start  -->

<section class="grid">

   <h1 class="heading">boekingen</h1>

   <div class="box-container">

   <?php
      $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
      $select_bookings->execute();
      if($select_bookings->rowCount() > 0){
         while($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>boeking id : <span><?= $fetch_bookings['booking_id']; ?></span></p>
      <p>naam : <span><?= $fetch_bookings['name']; ?></span></p>
      <p>email : <span><?= $fetch_bookings['email']; ?></span></p>
      <p>telefoonnummer : <span><?= $fetch_bookings['number']; ?></span></p>
      <p>check in : <span><?= $fetch_bookings['check_in']; ?></span></p>
      <p>uitchecken : <span><?= $fetch_bookings['check_out']; ?></span></p>
      <p>kamers : <span><?= $fetch_bookings['rooms']; ?></span></p>
      <p>volwassenen : <span><?= $fetch_bookings['adults']; ?></span></p>
      <p>kinderen : <span><?= $fetch_bookings['childs']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_bookings['booking_id']; ?>">
         <input type="submit" value="boeking verwijderen" onclick="return confirm('verwijder deze boeking?');" name="delete" class="btn">
      </form>
   </div>
   <?php
      }
   }else{
   ?>
   <div class="box" style="text-align: center;">
      <p>geen boekingen gevonden!</p>
      <a href="index.php" class="btn">ga naar huis</a>
   </div>
   <?php
      }
   ?>

   </div>

</section>

<!-- boekingen sectie eind -->
















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- eigen js file link  -->
<script src="js/admin_script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>