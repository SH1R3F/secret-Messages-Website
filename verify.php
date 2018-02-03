<?php require_once 'header.php';
?>
<div class="verifing">
  <div class="container">
    <div class="verify-box">
      <div class="status"></div>
      <?php
        if($page === 'verify.php' && $user->data()->email_verify ){
        ?>
        <h2 class="page-header">Your email is verified</h2>
        <p class="lead">
          This is your email  <?php echo $user->data()->email; ?>
        </p>
        <?php
      }else{
        ?>
        <h2 class="page-header">Verify Your Email</h2>
        <p class="lead">
          This is your email  <?php echo $user->data()->email; ?>
        </p>
        <input type="hidden" name="verify_token" value="<?php echo Token::generate('verify_token');?>"/>
        <button class="h1 btn btn-success">Yes. Send Verification link to my email</button>
        <?php
      }
      ?>
    </div>
  </div>
</div>
<?php require_once 'footer.php'; ?>
