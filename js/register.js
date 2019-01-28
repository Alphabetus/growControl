// frontend dynamic registration data validation will be here.
$(document).ready(function(){
  // error message > username > cleanup
  // $(".username-picked").hide();
  // error message > passwords > cleanup
  // $(".passwords-dont-match").hide();

  // graphical validation for username > on key up !
  $("#username_field").on("keyup", function(){
    if ($(this).val().length < 4 || $(this).val().length > 16 || /[<>?/!@#$%^&*()+.,;:]/.test($(this).val()) ){
      // username has wrong length or syntax
      $(this).removeClass("is-valid");
      $(this).addClass("is-invalid");
    }

    else {
      // username has right length
      // run ajax request to check if username is available
      jQuery.ajax({
      url: "ajax_requests/validate_register.php",
      data:'username_check=' + $("#username_field").val(),
      type: "POST",
      success:function(data){
        if (data === "free"){
          // username is free && valid
          $("#username_field").removeClass("is-invalid");
          $("#username_field").addClass("is-valid");
          $(".username-picked").hide();
        }
        else{
          // username is not free but valid
          $("#username_field").removeClass("is-valid");
          $("#username_field").addClass("is-invalid");
          $(".username-picked").show();
        }
      },
      error:function (){
        alert("Ajax request error on register.js\nPlease report.");
        return;
      }
      });

    }
  });
  // end of graphical validation for username


  // graphical validation for password length > on key up !
  $("#password_field").on("keyup", function (){
    if ($(this).val().length < 6 || $(this).val().length > 32){
      // password has wrong length
      $(this).removeClass("is-valid");
      $(this).addClass("is-invalid");
    }

    else{
      // password has correct length
      $(this).removeClass("is-invalid");
      $(this).addClass("is-valid");
    }
  });
  // end of graphical validation for password length > on key up !


  // graphical validation for password confirmation > on key up !
  $("#password_confirm_field").on("keyup", function(){
    if ($(this).val() === $("#password_field").val()){
      // passwords match > ok
      $(this).removeClass("is-invalid");
      $(this).addClass("is-valid");
      $(".passwords-dont-match").hide();
    }

    else {
      // passwords do not match > not ok
      $(this).removeClass("is-valid");
      $(this).addClass("is-invalid");
      $(".passwords-dont-match").show();
    }
  });
  // end of graphical validation for password confirmation > on key up !

});
