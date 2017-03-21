/**
 * Created by Rishabh on 10/21/15.
 */

var Restaurant = Restaurant || {};
var jphxr ;
Restaurant.controllers = {

    subtract:function(id){
        if(Restaurant.controllers.getQuantity(id)!=0)
            {
                Restaurant.controllers.quantity(id,-1);
                var over = '<div class="overlay">' +
                    '<img style="height:35px;width:35px" id="loading" src="'+full_url_asset+'/loading.gif">' +
                    '</div>';
                    $(over).appendTo($(".cart_container"));
                    $(".cart_container").css({'z-index' : '-99'});
            }
        
        
    },
    add:function(id){
        Restaurant.controllers.quantity(id,1);
        
        var over = '<div class="overlay">' +
            '<img style="height:35px;width:35px" id="loading" src="'+full_url_asset+'/loading.gif">' +
            '</div>';
        $(over).appendTo($(".cart_container"));
        $(".cart_container").css({'z-index' : '-99'});


    },

    quantity:function(id,quantity){
        var previous_quantity=Restaurant.controllers.getQuantity(id);
        quantity=previous_quantity+quantity;
        $$(id).innerHTML=quantity;
        id = id.substr(5);
        Restaurant.controllers.addToCart(id,quantity);
    },

    addToCart:function(id,quantity){
        
        if(jphxr != null)
        {
            jphxr.abort();
        }
        jphxr = App.utils.ajax('/add_to_cart/'+id+'/'+quantity, {
            'method': 'GET',
            'callback': Restaurant.controllers.addToCartCallback
        });
    },

    addToCartCallback:function(data){
        var orderDetails ;
        jphxr = null;
        try
        {
            var orderDetails=JSON.parse(data);
            if(orderDetails['message'] != null)
            {
                alert(orderDetails['message']);
            }
            $$('quantity-heading-responsive').innerHTML=orderDetails['dish_details'].length;
            var html="";
            
            
            html+="<span id=\"cart-details-image\"><img src='../img/assets/Cuisine.png' style=\"width:35px;margin-bottom: 5px;\"></span>"+
                "<span style=\"font-size: 14px;color: #975ba5;margin-left: 10px\" id=\"cart-details-items-quantity\">Your Tray <span id=\"quantity-heading\"><div class=\"num-items\">" 
                +orderDetails['dish_details'].length+"</div></span></span><div class=\"cart-list\">";
           
               
        

            $$('number-of-items').innerHTML=orderDetails['dish_details'].length;

            if(orderDetails['dish_details'].length>0){
                for(var i=0;i<orderDetails['dish_details'].length;i++)
                {
                    html+="<div class=\"cart-items\" id=\"cart-item-"+orderDetails['dish_details'][i]['rest_dish_id']+"\"><div style=\"float: left;width: 180px\">";
                    if(orderDetails['dish_details'][i]['veg_flag']==1){
                        html+="<img src='../img/assets/Veg.png' style=\"width: 16px; margin-right: 5px\">";
                    }else{
                            html+="<img src='../img/assets/NonVeg.png' style=\"width: 16px; margin-right: 5px\">";
                    }
                    html+=orderDetails['dish_details'][i]['dish_name'];
                    html+="</div>";
                   
                    html+="<div class=\"cart-item-price\" style=\"float: right;color: #975ba5;padding-right:5px\">Rs. <span  " +
                        "id=\"subtotal-"+orderDetails['dish_details'][i]['rest_dish_id']+"\">"+(orderDetails['dish_details'][i]['price'])*orderDetails['dish_details'][i]['quantity']+"</span></div>";

                    html+="<div style=\"clear: both;padding-left: 24px\"><img src='../img/assets/Minus.png' " +
                        "style=\"width: 16px;\" class=\"sub\" onclick=\"Cart.controllers.subtract("+orderDetails['dish_details'][i]['rest_dish_id']+")\">" +
                        "<span style=\"margin-right: 5px;margin-left:5px\" id=\""+orderDetails['dish_details'][i]['rest_dish_id']+"\">"+orderDetails['dish_details'][i]['quantity']+
                        "</span><img src='../img/assets/Plus.png' class=\"add\" style=\"width: 16px;\"onclick=\"Cart.controllers.add("+orderDetails['dish_details'][i]['rest_dish_id']+")\">" +
                        "<span style=\"font-size: 10px;color: #A79D9D \"> x Rs."+(orderDetails['dish_details'][i]['price'])+"</span></div></div>" +
                        "<div class=\"checkout-item-heading\" id=\"checkout-item-heading-"+orderDetails['dish_details'][i]['rest_dish_id']+"\" style=\"border-top: 1px solid #c7c4c4;margin: 15px 15px;\"></div>";

                }
                html+="</div><div class=\"sub-total-div\"> " +
//                    "<div class=\"cart-items\"> <div style=\"float:left;color: #909090;width: 180px\">Sub Total</div> <div style=\"float: right;padding-right: 20px;\" class=\"cart-item-price\"> Rs. <span id=\"subtotal-all\">"+orderDetails['total']['total_amount']+"</span> </div> </div> " +
                    "<div class=\"cart-items\" style=\"clear:both;color: #975ba5; font-size: 16px;\"> <div style=\"float:left;font-size: 16px;margin-bottom: 10px\">Item Total</div> <div class=\"cart-item-price\" style=\"float: right; padding-right: 18px;margin-bottom: 10px\"> Rs. <span id=\"subtotal-all\">"+orderDetails['total']['total_amount']+
                    "</span> </div> </div> <div style=\"clear:both;background-color: #975ba5; color: #fff; text-align: center;margin-top:20px; line-height: 2.5; cursor: pointer;\" onclick=\"window.location.href='/confirm_checkout'\"> Checkout </div>"

            }else{
                html+="<div style=\"margin: 24px auto; text-align: center\"> <img src='../img/assets/CartEmpty.png'> " +
                    "<p style=\"margin: 8px 24px; font-size: 24px; line-height: 1.3; color: #c0c0c0;\"> Add Something Will You ?</p> </div>";
            }
            html+="</div>";
            if($$('cart'))
            {
                $$('cart').innerHTML=html;
                $(".cart_container").css({'z-index' : ''});
                $('.overlay').fadeOut(500, function(){ $(this).remove();});
            }
            else
            {
                location.reload();
            }
        }
        catch(e)
        {
            
        }
        
    },
    getQuantity:function(id){
        console.log(id + ':'+$$(id).innerHTML);
        return parseInt($$(id).innerHTML);
    }

};
