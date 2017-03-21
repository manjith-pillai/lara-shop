var PersonalDetails = PersonalDetails || {};
var jphxr;
PersonalDetails.controllers = {

    init: function()
    {
        
        on($$('signIn'), 'click', PersonalDetails.controllers.displaysignin);
        on($$('signIn-page'), 'click', PersonalDetails.controllers.displaysignin);
        on($$('signIn-page-block'), 'click', PersonalDetails.controllers.displaysignin);
        

        on($$('signUp-page'), 'click', PersonalDetails.controllers.displaysignup);
        on($$('signUp'), 'click', PersonalDetails.controllers.displaysignup);
        
    },
    
    displaysignin:function()
    {
        if($$('signIn-page').style.display!='block')
            $$('signIn-page').style.display='block';
        else
            $$('signIn-page').style.display='none';
    },

    displaysignup:function()
    {
        if($$('signUp-page').style.display!='block')
            $$('signUp-page').style.display='block';
        else
            $$('signUp-page').style.display='none';
    },
    
    
    signin:function()
    {

        alert("login function called"); 
        $.post(window.location.post='/auth/login', function(data){
        if(data == "")
        {
            alert('No user data on record');  
        }
        else
        { 
            //just alert the data for now
            alert(data);
            $$('signIn-page').innerHTML = data ;
        }

            }) ;
        //TODO Login Page Call
    },

    saveUserDetails:function()
    {
        var name=$$('full_name').value;
        var email=$$('customer_email').value;
        var phone=$$('phone').value;
        var area=$$('area').value;
        var building=$$('building').value;


        if(full_name=='' || customer_email=='' || phone=='' || building=='' || area==''){
            //alert('No field can be left blank');
            //return;
        }

        var postData = {
            'email' : $$('customer_email').value,
            'phone': $$('phone').value,
            'specialinstructions': $$('specialinstructions').value,
            'area': $$('area').value,
            'building':$$('building').value,
            'address' : $$('building').value+',\u00A0'+$$('area').value,
            'name':$$('full_name').value,
            'donation':$$('donation').value,
            'delivery':$('input[name=delivery]:checked').val(),
            'amount':$$('total-all').innerHTML
        };
        App.utils.post('/make_payment', postData);
    },
    verifyOTP:function()
    {
        var verify_code = $$('otp_code').value;
        if(verify_code == 0)
        {
            $( "#otp_error" ).html( "<p class='error-alert' id=''>Please enter your OTP.</p>" );
        }
        var phone =  $$('phone').value;
        jphxr = App.utils.ajax('/verify_mobile/'+phone+'/'+verify_code, {
            'method': 'GET',
            'dataType': 'json',
            'data':'verify',
            'callback': PersonalDetails.controllers.mobileVerifationCallback
        });
    },
    mobileVerifationCallback:function(verifcation_resp) 
    {
        var verifcationResp = JSON.parse(verifcation_resp);
        //console.log(verifcationResp.verify_status);
        if(verifcationResp.verify_status == 0) {
           //$( "<p class=''error-alert' id=''>Please enter correct OTP.</p>" ).insertAfter("#otp_code");
           $( "#otp_error" ).html( "<p class='error-alert' id=''>Invalid OTP.</p>" );
        } else {
            var name=$$('full_name').value;
            var email=$$('customer_email').value;
            var phone=$$('phone').value;
            var area=$$('area').value;
            var building=$$('building').value;


            if(full_name=='' || customer_email=='' || phone=='' || building=='' || area==''){
                //alert('No field can be left blank');
                //return;
            }

            var postData = {
                'email' : $$('customer_email').value,
                'phone': $$('phone').value,
                'specialinstructions': $$('specialinstructions').value,
                'area': $$('area').value,
                'building':$$('building').value,
                'address' : $$('building').value+' '+$$('area').value,
                'name':$$('full_name').value,
                'donation':$$('donation').value,
                'delivery':$('input[name=delivery]:checked').val(),
                'amount':$$('total-all').innerHTML
            };
            App.utils.post('/make_payment', postData);
        }
    },
    updatePaymentCallBack:function()
    {

    }
};