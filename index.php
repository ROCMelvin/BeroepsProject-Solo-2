<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');
   header('location:index.php');
}

if(isset($_POST['check'])){

   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   // als het hotel maar 10 kamers heeft
   if($total_rooms >= 10){
      $warning_msg[] = 'Kamers zijn niet beschikbaar';
   }else{
      $success_msg[] = 'Kamers zijn beschikbaar';
   }

}

if(isset($_POST['book'])){

   $booking_id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $rooms = $_POST['rooms'];
   $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
   $check_out = $_POST['check_out'];
   $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
   $adults = $_POST['adults'];
   $adults = filter_var($adults, FILTER_SANITIZE_STRING);
   $childs = $_POST['childs'];
   $childs = filter_var($childs, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   if($total_rooms >= 10){
      $warning_msg[] = 'Kamers zijn niet beschikbaar';
   }else{

      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

      if($verify_bookings->rowCount() > 0){
         $warning_msg[] = 'Kamer al geboekt!';
      }else{
         $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
         $success_msg[] = 'Kamer succesvol geboekt!';
      }

   }

}

if(isset($_POST['send'])){

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_message->execute([$name, $email, $number, $message]);

   if($verify_message->rowCount() > 0){
      $warning_msg[] = 'Bericht al verzonden!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$id, $name, $email, $number, $message]);
      $success_msg[] = 'Bericht succesvol verzonden!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Hotel Ter Duin</title>

   <!-- slider link  -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- eigen css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>


<!-- home sectie start -->

<section class="home" id="home">

   <div class="swiper home-slider">

      <div class="swiper-wrapper">

         <div class="box swiper-slide">
            <img src="images/home-img-1.jpg" alt="">
            <div class="flex">
               <h3>Hotel Ter Duin</h3>
               <a href="#availability" class="btn">Beschikbaarheid controleren</a>
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="images/home-img-2.jpg" alt="">
            <div class="flex">
               <h3>eten en drinken</h3>
               <a href="#reservation" class="btn">Reserveren</a>
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="images/home-img-3.jpg" alt="">
            <div class="flex">
               <h3>luxe kamers</h3>
               <a href="#contact" class="btn">Neem contact met ons op</a>
            </div>
         </div>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

   </div>

</section>

<!-- home sectie eind -->

<!-- beschikbaarheid sectie start  -->

<section class="availability" id="availability">

   <form action="" method="post">
      <div class="flex">
         <div class="box">
            <p>Inchecken <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>Uitchecken<span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>Volwassenen <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1">1 volwassene</option>
               <option value="2">2 volwassenen</option>
               <option value="3">3 volwassenen</option>
               <option value="4">4 volwassenen</option>
               <option value="5">5 volwassenen</option>
               <option value="6">6 volwassenen</option>
            </select>
         </div>
         <div class="box">
            <p>Kinderen <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="-">geen kind</option>
               <option value="1">1 kind</option>
               <option value="2">2 kinderen</option>
               <option value="3">3 kinderen</option>
               <option value="4">4 kinderen</option>
               <option value="5">5 kinderen</option>
               <option value="6">6 kinderen</option>
            </select>
         </div>
         <div class="box">
            <p>Kamer <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1">1 kamer</option>
               <option value="2">2 kamers</option>
               <option value="3">3 kamers</option>
               <option value="4">4 kamers</option>
               <option value="5">5 kamers</option>
               <option value="6">6 kamers</option>
            </select>
         </div>
      </div>
      <input type="submit" value="beschikbaarheid controleren" name="check" class="btn">
   </form>

</section>

<!-- beschikbaarheid sectie eind -->

<!-- over ons sectie start  -->

<section class="about" id="about">

   <div class="row">
      <div class="image">
         <img src="images/about-img-1.jpg" alt="">
      </div>
      <div class="content">
         <h3>de beste medewerkers</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi laborum maxime eius aliquid temporibus unde?</p>
         <a href="#reservation" class="btn">reserveren</a>
      </div>
   </div>

   <div class="row revers">
      <div class="image">
         <img src="images/about-img-2.jpg" alt="">
      </div>
      <div class="content">
         <h3>het beste eten</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi laborum maxime eius aliquid temporibus unde?</p>
         <a href="#contact" class="btn">Neem contact met ons op</a>
      </div>
   </div>

   <div class="row">
      <div class="image">
         <img src="images/about-img-3.jpg" alt="">
      </div>
      <div class="content">
         <h3>zwembad</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi laborum maxime eius aliquid temporibus unde?</p>
         <a href="#availability" class="btn">beschikbaarheid controleren</a>
      </div>
   </div>

</section>

<!-- over ons sectie eind  -->

<!-- services sectie start  -->

<section class="services">

   <div class="box-container">

      <div class="box">
         <img src="images/icon-1.png" alt="">
         <h3>eten & drinken</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

      <div class="box">
         <img src="images/icon-2.png" alt="">
         <h3>dineren</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

      <div class="box">
         <img src="images/icon-3.png" alt="">
         <h3>zwembad</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

   </div>

</section>

<!-- services sectie eind  -->

<!-- reservering sectie start  -->

<section class="reservation" id="reservation">

   <form action="" method="post">
      <h3>Reserveren</h3>
      <div class="flex">
         <div class="box">
            <p>Uw naam <span>*</span></p>
            <input type="text" name="name" maxlength="50" required placeholder="Vul uw naam in" class="input">
         </div>
         <div class="box">
            <p>Uw  email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="Voer uw e-mailadres in" class="input">
         </div>
         <div class="box">
            <p>Uw  telefoonnummer <span>*</span></p>
            <input type="number" name="number" maxlength="10" min="0" max="9999999999" required placeholder="Voer uw nummer in" class="input">
         </div>
         <div class="box">
            <p>Kamers <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 kamer</option>
               <option value="2">2 kamers</option>
               <option value="3">3 kamers</option>
               <option value="4">4 kamers</option>
               <option value="5">5 kamers</option>
               <option value="6">6 kamers</option>
            </select>
         </div>
         <div class="box">
            <p>Uitchecken<span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>Uitchecken <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>Volwassenen <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 volwassene</option>
               <option value="2">2 volwassenen</option>
               <option value="3">3 volwassenen</option>
               <option value="4">4 volwassenen</option>
               <option value="5">5 volwassenen</option>
               <option value="6">6 volwassenen</option>
            </select>
         </div>
         <div class="box">
            <p>Kinderen <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0" selected>geen kind</option>
               <option value="1">1 kind</option>
               <option value="2">2 kinderen</option>
               <option value="3">3 kinderen</option>
               <option value="4">4 kinderen</option>
               <option value="5">5 kinderen</option>
               <option value="6">6 kinderen</option>
            </select>
         </div>
      </div>
      <input type="submit" value="boek nu" name="book" class="btn">
   </form>

</section>

<!-- reservering sectie eind  -->

<!-- galerij sectie start -->

<section class="gallery" id="gallery">

   <div class="swiper gallery-slider">
      <div class="swiper-wrapper">
         <img src="images/gallery-img-1.jpg" class="swiper-slide" alt="">
         <img src="images/gallery-img-2.jpg" class="swiper-slide" alt="">
         <img src="images/gallery-img-3.jpg" class="swiper-slide" alt="">
         <img src="images/gallery-img-4.jpg" class="swiper-slide" alt="">
         <img src="images/gallery-img-5.jpg" class="swiper-slide" alt="">
         <img src="images/gallery-img-6.jpg" class="swiper-slide" alt="">
      </div>
      <div class="swiper-pagination"></div>
   </div>

</section>

<!-- galerij sectie eind  -->

<!-- contact sectie start  -->

<section class="contact" id="contact">

   <div class="row">

      <form action="" method="post">
         <h3>Stuur ons bericht</h3>
         <input type="text" name="name" required maxlength="50" placeholder="Vul uw naam in" class="box">
         <input type="email" name="email" required maxlength="50" placeholder="Voer uw e-mailadres in" class="box">
         <input type="number" name="number" required maxlength="10" min="0" max="9999999999" placeholder="Voer uw nummer in" class="box">
         <textarea name="message" class="box" required maxlength="1000" placeholder="Voer uw bericht in" cols="30" rows="10"></textarea>
         <input type="submit" value="bericht versturen" name="send" class="btn">
      </form>

      <div class="faq">
         <h3 class="title">Veel Gestelde Vragen</h3>
         <div class="box active">
            <h3>Hoe te annuleren?</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus sunt aspernatur excepturi eos! Quibusdam, sapiente.</p>
         </div>
         <div class="box">
            <h3>Is er een vacature?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
         <div class="box">
            <h3>Wat zijn de betaalmethoden?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
         <div class="box">
            <h3>Wat zijn de openingstijden?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
      </div>

   </div>

</section>

<!-- contact sectie eind  -->





<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- eigen js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>