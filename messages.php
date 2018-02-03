<?php require_once 'header.php';
$actual_link = str_replace("messages.php", "", $actual_link);
?>
<div class="container">
  <section class="my-details">
    <?php
      if($user->data()->photo === ''){
        $image_src = 'http://placehold.it/100';
      }else{
        $image_src = $user->data()->photo;
      }
    ?>
    <div class="my-img" style="background-image: url('<?php echo $image_src; ?>');"></div>
    <h4 class="bold">
      <a href="settings.php"><i class="fa fa-cog"></i></a>
       <?php echo $user->data()->name;?>
     </h4>
     <a id="user_id" data-hold="<?php echo $user->data()->id; ?>"></a>
     <h4 class="bold">Messages: <span><?php echo Messages::getInstance()->GetAllData($user->data()->id)->count(); ?></span></h4>
     <a href="<?php echo $actual_link . "send.php?user=" . $user->data()->username; ?>" class="bold"><?php echo $actual_link . "send.php?user=" . $user->data()->username; ?></a>
  </section>
  <section class="my-messages">
    <h2 class="page-header"><i class="fa fa-comments"></i> Messages</h2>
    <div class="row">
      <div class="col-xs-6 messages-cat category active" data-filter="cat-all">Received</div>
      <div class="col-xs-6 messages-cat category" data-filter="loved">Favorite</div>
    </div>
    <div class="messages">
      <input type="hidden" name="messages_csrf" value="<?php echo Token::generate('messages_csrf');?>" />
      <?php
      $allMsgs = Messages::getInstance()->GetAllData($user->data()->id)->result();
      if(count($allMsgs)){
        foreach($allMsgs as $stringed){
          $newArr[] = json_encode($stringed);
        }
        $pag = new Pagination();
        $limit = 50;
        $nums = $pag->paginate($newArr, $limit);
        foreach($pag->fetchResults() as $message_1){
          $message_1 = json_decode($message_1);
        ?>
          <div class="message cat-all <?php echo ($message_1->category == 0)? 'received' : 'loved'; ?>">
            <span class="delete" data-hold="<?php echo $message_1->id; ?>"><i class="fa fa-window-close"></i></span>
            <p class="lead">
              <?php echo $message_1->message; ?>
            </p>
            <span class="info"><a class="date"><?php echo date('g:i a - jS F Y', strtotime($message_1->SentDate)); ?></a></span>
            <span class="love" data-hold="<?php echo $message_1->id; ?>"><i class="fa fa-heart"></i></span>
          </div>
        <?php
        }
        if(count($nums) > 1){
          echo '<div class="pagination">';
          foreach($nums as $num){
            $active = (Input::get('page') == $num)? 'active': '';
            echo '<a class="' . $active . '" href="messages.php?page='.$num.'"> '.$num.' </a>';
          }
          echo '</div>';
        }
      }else{
        ?>
        <div class="alert alert-info" style="margin-top: 35px;">
          <p class="lead">
            You don't have messages yet.. You need to share your link with your friends
          </p>
          Your Link is <a href="//<?php echo $actual_link . "send.php?user=" . $user->data()->username; ?>" target="_blank"> <?php echo $actual_link . "send.php?user=" . $user->data()->username; ?> </a>
        </div>
        <?php
      }
      ?>
    </div>
  </section>
</div>

<?php require_once 'footer.php'; ?>
