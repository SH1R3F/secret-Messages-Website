/*~~~~~~~~~~~~~* Constant Functions *~~~~~~~~~~~~~*/
$(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();
    $('html, body').animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 500);
});

/*~~~~~~~~* working on placeholder *~~~~~~~~*/
function placeholderFocus(element){
  element.focus(function(){
    element.attr("placeholderSaver", element.attr("placeholder"));
    element.removeAttr("placeholder");
  });
}
function placeholderBlur(element){
    if(element.val() === ''){
      element.attr("placeholder", element.attr("placeholderSaver"));
      element.removeAttr("placeholderSaver");
    }
}
$(document).ready(function(){
  /*~~~~~~~~~~~~~* REGISTRATION VALIDATION *~~~~~~~~~~~~~*/

  if( $("#register_page").length > 0 ){

    placeholderFocus($("#name"));
    placeholderFocus($("#email"));
    placeholderFocus($("#username"));

    $("#name").blur(function(){

      placeholderBlur( $(this) );
      var value = $("#name").val();

      /*-----------* VALIDATE INPUT *-----------*/
      if(value === ''){
        $(".errName")[0].innerHTML = "Please Enter your name";
      }else if( !/^[\w абвгдеёжзийклмнопрстуфхцчшщъыьэю\u0600-\u06FF]+$/.test( value.toLowerCase() ) ){
        $(".errName")[0].innerHTML = "Your name must contain only letters and numbers";
      }else if( value.length < 4 ){
        $(".errName")[0].innerHTML = "The Name Must Be At Least 4 Characters";
      }else if( value.length > 50 ){
        $(".errName")[0].innerHTML = "The Name Must Be At Most 50 Characters";
      }else{
        $(".errName")[0].innerHTML = "";
      }

    });
    $("#email").blur(function(){
      placeholderBlur( $(this) );
      var value = $("#email").val();

      /*-----------* VALIDATE INPUT *-----------*/
      if(value === ''){
        $(".errEmail")[0].innerHTML = "Please Enter Your Email";
        errEmail = 1;
      }else if( !/^[\w\.]+@[A-z_0-9]+\.[A-z0-9]+$/.test( value.toLowerCase() ) ){
        $(".errEmail")[0].innerHTML = "Please Enter a valid Email";
        errEmail = 1;
      }else if( value.length < 5 ){
        $(".errEmail")[0].innerHTML = "Email Must Be At Least 5 digits";
        errEmail = 1;
      }else if( value.length > 70 ){
        $(".errEmail")[0].innerHTML = "Email Must Be At Most 70 digits";
        errEmail = 1;
      }else{
        //check for existing!
        $.post(
          'ajaxRequests.php',
          {
            'email': value
          },
          function(data, status){
            if(status === 'success'){
              if(data === '1'){
                $(".errEmail")[0].innerHTML = "This email is already a user - <a href='login.php'>Login</a>";
                errUser = 1;
              }else{
                $(".errEmail")[0].innerHTML = "";
                errEmail = 0;
              }
            }
          }
        );
      }

    });
    $("#password").blur(function(){
      placeholderBlur( $(this) );
      var value = $("#password").val();
      /*-----------* VALIDATE INPUT *-----------*/
      if(value === ''){
        $(".errPass")[0].innerHTML = "You Must Enter a Password";
      }else if( !/^[\w абвгдеёжзийклмнопрстуфхцчшщъыьэю\.\-~@\u0600-\u06FF]+$/i.test( value ) ){
        $(".errPass")[0].innerHTML = "Password must be in letters, numbers, dashes, underscores and spaces";
      }else if( value.length < 8 ){
        $(".errPass")[0].innerHTML = "Password Must Be At Least 8 digits";
      }else if( value.length > 80 ){
        $(".errPass")[0].innerHTML = "Password Must Be At Most 80 digits";
      }else{
        $(".errPass")[0].innerHTML = "";
      }
    });
    $("#username").blur(function(){
      placeholderBlur( $(this) );
      var value = $("#username").val();
      /*-----------* VALIDATE INPUT *-----------*/
      if(value === ''){
        $(".errUser")[0].innerHTML = "Please Enter a username";
      }else if( !/^\w*[a-zA-Z]\w*$/i.test( value ) ){
        $(".errUser")[0].innerHTML = "Username must be only in English alphabets and can't contain symbols except underscores, and must contain at least one letter";
      }else if( value.length < 2 ){
        $(".errUser")[0].innerHTML = "The Username Must Be At Least 2 Characters";
      }else if( value.length > 50 ){
        $(".errUser")[0].innerHTML = "The Username Must Be At Most 50 Characters";
      }else{
        //check for existing!
        $.post(
          'ajaxRequests.php',
          {
            'username': value
          },
          function(data, status){
            if(status === 'success'){
              if(data === '1'){
                $(".errUser")[0].innerHTML = "Sorry this user name is taken!";
              }else{
                $(".errUser")[0].innerHTML = "";
              }
            }
          }
        );
      }
    });
    $("#gender").blur(function(){
      placeholderBlur( $(this) );
      var value = $("#gender").val();
      /*-----------* VALIDATE INPUT *-----------*/
      if(value === ''){
        $(".errGend")[0].innerHTML = "Please Enter your Gender";
      }else{
        $(".errGend")[0].innerHTML = "";
      }
    });

  }
  if( $("#settings").length ){
    //your code here
    $("li.settings-cat").click(function(e){
      e.preventDefault();
      $(this).addClass('active').siblings().removeClass('active');
    });
  }

  if( $(".my-messages .messages").length ){

    /*~~~~~~~~~~~~~* GALLERY EFFECT *~~~~~~~~~~~~~*/
    $(".my-messages .messages-cat").click(function(){
      var fil_ter = $(this).attr("data-filter");
      $(".my-messages .messages .message").not("."+fil_ter).hide(400, 'swing');
      $(".my-messages .messages .message."+fil_ter).show(400, 'swing');
      $(this).addClass("active").siblings().removeClass("active");
    })

    /*~~~~~~~~~~~~~* ENABLE LOVE BUTTON *~~~~~~~~~~~~~*/
    $(".my-messages .messages span.love").click(function(){
      var MsgId = $(this).attr('data-hold');
      var th_is = $(this);
      $.post(
        'ajaxRequests.php',
        {
          'msg_id': MsgId,
          'csrf_token_like': $("input[name=messages_csrf]").val(),
          'set': 1
        },
        function(data, status){
          if(status === 'success'){
            //set loved class for parent
            if(data == 1){
              th_is.closest(".cat-all").addClass('loved');
            }else{
              th_is.closest(".cat-all").removeClass('loved');
            }
          }
        }
      );
    });

    /*~~~~~~~~~~~~~* ENABLE DELETE BUTTON *~~~~~~~~~~~~~*/
    $(".my-messages .messages span.delete").click(function(){
      var MsgId = $(this).attr('data-hold');
      var th_is = $(this);
      $.post(
        'ajaxRequests.php',
        {
          'remove_msg_id': MsgId,
          'csrf_token_remove': $("input[name=messages_csrf]").val(),
        },
        function(data, status){
          if(status === 'success'){
            th_is.closest(".cat-all").remove();
          }
        }
      );
    });
  }



if($(".verifing").length){
  $(".verifing .verify-box button").click(function(){
    $.post(
      'ajaxRequests.php',
      {
        'verify_token': $("input[name=verify_token]").val(),
      },
      function(data, status){
        if(status === 'success'){
          $(".status")[0].outerHTML = "<p class='alert alert-info lead' style='margin-top: 10px;'>We've sent you an email</p>";
        }
      }
    );
  });
}
});
