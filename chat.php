<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php 
          $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
          if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
          }else{
            header("location: users.php");
          }
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/<?php echo $row['img']; ?>" alt="">
        <div class="details">
          <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
          <p><?php echo $row['status']; ?></p>
          <div class="nature">
          <button id="toggleColors" class="sea"><i class="fa-solid fa-droplet"></i></button>
          <button id="ToggleColors" class="sun"><i class="fa-solid fa-sun"></i></button>
          <button id="TOggleColors" class="leaf"><i class="fa-solid fa-leaf"></i></button>
        </div>
        </div>
      </header>
      <div class="chat-box"  id="chatBox">

      </div>
      <form action="#" class="typing-area" enctype="multipart/form-data" id="fileFrom">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Mesajınızı girin..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button> 
      </form>
    </section>
  </div>

  <script src="javascript/chat.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const chatBox = document.getElementById('chatBox');
      const toggleColorsButton = document.getElementById('toggleColors');

      toggleColorsButton.addEventListener('click', function() {
        // Chatbox arka plan rengini siyah yap
        chatBox.style.backgroundColor = '#1e90ff';

      });
    });
  </script>
      <script>
    document.addEventListener('DOMContentLoaded', function() {
      const chatBox = document.getElementById('chatBox');
      const toggleColorsButton = document.getElementById('ToggleColors');

      toggleColorsButton.addEventListener('click', function() {
        // Chatbox arka plan rengini siyah yap
        chatBox.style.backgroundColor = '#ffff00';

      });
    });
  </script>
   <script>
    document.addEventListener('DOMContentLoaded', function() {
      const chatBox = document.getElementById('chatBox');
      const toggleColorsButton = document.getElementById('TOggleColors');

      toggleColorsButton.addEventListener('click', function() {
        // Chatbox arka plan rengini siyah yap
        chatBox.style.backgroundColor = '#00ff00';

      });
    });
  </script>
</body>
</html>
