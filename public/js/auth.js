 $(function(){
        jQuery('#myTab a:last').tab('show');
      });

      $(function(){
        $('#register-form').submit(function(e){
          e.preventDefault();
          var formURL = $(this).attr("action");
          var postData = $(this).serialize();
          $.ajax({
            url:formURL,
            method:'post',
            dataType:'json',
            data:postData,
            success:function(data){

   
            },
            error:function(data){
             
              if(data.status == 200) {
                window.location = '/';  
              }
              if(data.status == 422) {
                var errors = data.responseJSON;
                if(typeof errors.name !="undefined") {
                  errorsHtml = '<div class="alert-danger-custom" style="margin-top:5px">'+errors.name+'</div>';
                  var over = '<div class="overlay">' +
                    '<img style="height:35px;width:35px" id="loading" src="'+full_url_asset+'/loading.gif">' +
                    '</div>';
                  $(over).appendTo($(".register_container"));
                  $('.overlay').fadeOut(1000, function(){ $(this).remove();});
                  $( '#form-errors' ).html( errorsHtml );
                  $('#form-errors').show(); 
                } else {
                  $('#form-errors').hide(); 
                }
                if(typeof errors.email !="undefined") {
                  errorsHtml1 = '<div class="alert-danger-custom" style="margin-top:5px">'+errors.email+'</div>';
                  $( '#form-errors1' ).html( errorsHtml1 );
                  $('#form-errors1').show();
                } else {
                  $('#form-errors1').hide();
                }
                if(typeof errors.password !="undefined") {
                  errorsHtml2 = '<div class="alert-danger-custom" style="margin-top:5px">'+errors.password+'</div>';
                  $( '#form-errors2' ).html( errorsHtml2 );
                  $('#form-errors2').show();
                } else {
                  $('#form-errors2').hide();
                }
                
              }

            }
          });
        });

    $('#sign-in-form').submit(function(e){
          e.preventDefault();
          var formURL = $(this).attr("action");
          var postData = $(this).serialize();
          $.ajax({
            url:formURL,
            method:'post',
            dataType:'json',
            data:postData,
            success:function(data){

              },
            error:function(data){
              //alert(data.status);
             if(data.status == 200) {
               window.location = '/auth/login';

              } 

             if(data.status == 422){
              var errors = data.responseJSON;
              if(typeof errors.email !="undefined") {
              errorsHtmlsign = '<div class="alert-danger-custom" style="margin-top:5px">'+errors.email+'</div>';
              var over = '<div class="overlay">' +
                    '<img style="height:35px;width:35px" id="loading" src="'+full_url_asset+'/loading.gif">' +
                    '</div>';
              $(over).appendTo($(".signin_container"));
              $('.overlay').fadeOut(1000, function(){ $(this).remove();});
              $( '#sign-errors' ).html( errorsHtmlsign );
              $('#sign-errors').show();
            }else {
                  $('#sign-errors').hide(); 
                }

              if(typeof errors.password !="undefined"){ 
              errorsHtmlsign1 = '<div class="alert-danger-custom" style="margin-top:5px">'+errors.password+'</div>';
              $( '#sign-errors1' ).html( errorsHtmlsign1 );
              $('#sign-errors1').show();
              }else {
                  $('#sign-errors1').hide(); 
                }
            }
             
            }
          });
        });

});

    