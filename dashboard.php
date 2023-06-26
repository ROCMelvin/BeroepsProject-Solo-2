<?php

include 'components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- eigen css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<!-- header sectie start  -->
<?php include 'components/admin_header.php'; ?>
<!-- header sectie eind -->

<!-- dashboard sectie start  -->

<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

   <div class="box">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ? LIMIT 1");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <h3>Welkom!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update.php" class="btn">Update Profiel</a>
   </div>

   <div class="box">
      <?php
         $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
         $select_bookings->execute();
         $count_bookings = $select_bookings->rowCount();
      ?>
      <h3><?= $count_bookings; ?></h3>
      <p>Totale Boekingen</p>
      <a href="bookings.php" class="btn">Boekingen Bekijken</a>
   </div>

   <div class="box">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `admins`");
         $select_admins->execute();
         $count_admins = $select_admins->rowCount();
      ?>
      <h3><?= $count_admins; ?></h3>
      <p>Totale Admins</p>
      <a href="admins.php" class="btn">Admins Bekijken</a>
   </div>

   <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         $count_messages = $select_messages->rowCount();
      ?>
      <h3><?= $count_messages; ?></h3>
      <p>Totale Berichten</p>
      <a href="messages.php" class="btn">Berichten Bekijken</a>
   </div>

   <div class="box">
      <h3>Snel Selecteren</h3>
      <p>Login of Registreer</p>
      <a href="login.php" class="btn" style="margin-right: 1rem;">Login</a>
      <a href="register.php" class="btn" style="margin-left: 1rem;">Registreer</a>
   </div>

   </div>

</section>


<!-- dashboard sectie eind -->




















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- eigen js file link  -->
<script src="js/admin_script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>