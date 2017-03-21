/**
 * Created by Rishabh on 9/17/15.
 */
var Cart = Cart || {};
var jphxr ;
var rest_menu = document.getElementById('restaurant_menu');
var rest_list = document.getElementById('restaurant-list');
var homepage = document.getElementById('home');
//console.log(rest_list);
Cart.controllers = {

    init: function() {
		//console.log(rest_menu);
        if(rest_menu)
        {
            $$('cart').style.display='block';

            
        }else if(rest_list)
            {
                
                $$('cart').style.display='none';
            }
            else if(homepage)
            {
                $$('cart').style.display='none';
            }
            else{
        on($$('show_cart'), 'click', Cart.controllers.displayCart);
        on($$('cart-details-image'), 'click', Cart.controllers.displayCart);
        on($$('cart-details-items-quantity'), 'click', Cart.controllers.displayCart);}
        // if(rest_list)
        // {
        //     $$('cart').style.display='none';
        // }else{
        //     on($$('show_cart'), 'click', Cart.controllers.displayCart);
        // on($$('cart-details-image'), 'click', Cart.controllers.displayCart);
        // on($$('cart-details-items-quantity'), 'click', Cart.controllers.displayCart);
        // }
    },
    displayCart:function(){
        if($$('cart').style.display!='block')
            $$('cart').style.display='block';
        else
            $$('cart').style.display='block';
    },
    cartResult:function(data){
        //console.log(data);
    },
    subtract:function(id){
        var quantity = Cart.controllers.getQuantity(id);
        var quantityUpdated = quantity-1;
        if(quantity<=1)
        {
            if($$('cart-item-'+id))
		      $$('cart-item-'+id).remove();
		    if($$('checkout-item-heading-'+id))
                $$('checkout-item-heading-'+id).remove();
        }
        if(quantityUpdated >0)
        {
            Cart.controllers.quantity(id,-1);

            console.log('quantityUpdated > 0:'+quantityUpdated);
        } else if(quantityUpdated ==0)
        {
            Cart.controllers.quantity(id,0);
            console.log('quantityUpdated :'+quantityUpdated);
        }
        else
        {
            Cart.controllers.quantity(id,0-quantityUpdated);

            console.log('else quantityUpdated :'+quantityUpdated);
        }
    },
    add:function(id){
        Cart.controllers.quantity(id,1);
    },
    
    quantity:function(id,quantity){
        var previous_quantity=Cart.controllers.getQuantity(id);
        quantity=previous_quantity+quantity;
        console.log('sending quantity :'+previous_quantity);
        if($$(id))
        {
            $$(id).innerHTML=quantity;
        }
        if($$('rest-'+id))
        {
            $$('rest-'+id).innerHTML=quantity;   
        }
        var subtotal = $$('subtotal-'+id);
        if (subtotal != null)
        {
            var subtotalPrice=$$('subtotal-all').innerHTML-$$('subtotal-'+id).innerHTML;
            if(previous_quantity!=0)
                var price=(($$('subtotal-'+id).innerHTML)/previous_quantity)*quantity;
            else
                var price=0;
            subtotalPrice=subtotalPrice+price;
            $$('subtotal-'+id).innerHTML=price;
            //$$('subtotal-all').innerHTML=subtotalPrice;
            //$$('total-all').innerHTML=subtotalPrice;
        }
        Cart.controllers.addToCart(id,quantity);
        if(previous_quantity<=0 && subtotal != null)
        {
            alert("Previous Quantity Already zero some issue " ) ;
            Cart.controllers.addToCart(id,0);
        }
    },
    addToCart:function(id,quantity){
        if(jphxr != null)
        {
            jphxr.abort();
        }
        jphxr = App.utils.ajax('/add_to_cart/'+id+'/'+quantity, {
            'method': 'GET',
            'callback': Cart.controllers.addToCartCallback
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
            
            if($('#quantity-heading').length > 0) {
                $$('quantity-heading').innerHTML='<div class="num-items">'+orderDetails['dish_details'].length+'</div>';
            }
            if($('#subtotal-all').length > 0) {
                $$('subtotal-all').innerHTML=orderDetails['orderinfo'].total_amount;
            }
            if($('#package-charge').length > 0) {
                $$('package-charge').innerHTML = orderDetails['orderinfo'].total_packcharge;
            }
            if($('#service-tax').length > 0) {
                $$('service-tax').innerHTML=orderDetails['orderinfo'].total_servtax;
            }
            if($('#vat').length > 0) {
                $$('vat').innerHTML=orderDetails['orderinfo'].total_vat;
            }
            if($('#total-all').length > 0) {
               $$('total-all').innerHTML=orderDetails['orderinfo'].total_with_tax; 
            }
            if(orderDetails['dish_details'].length == 0) {
                var html="";
                html+="<span id=\"cart-details-image\"><img src='../img/assets/Cuisine.png' style=\"width:35px;margin-bottom: 5px;\"></span>"+
                "<span style=\"font-size: 14px;color: #975ba5;margin-left: 10px\" id=\"cart-details-items-quantity\">Your Tray <span id=\"quantity-heading\"><div class=\"num-items\">" 
                +orderDetails['dish_details'].length+"</div></span></span><div class=\"cart-list\">";
                html+="<div style=\"margin: 24px auto; text-align: center\"> <img src='../img/assets/CartEmpty.png'> " +
                    "<p style=\"margin: 8px 24px; font-size: 24px; line-height: 1.3; color: #c0c0c0;\"> Add Something Will You ?</p> </div>";
                html+="</div>";
                if($$('cart')) {
                    $$('cart').innerHTML=html;
                    $(".cart_container").css({'z-index' : ''});
                    $('.overlay').fadeOut(500, function(){ $(this).remove();});
                }
            }
            
        }
        catch(e)
        {
            
        }

    },
    getQuantity:function(id){
        if($$(id))
        {
            console.log(id +': '+parseInt($$(id).innerHTML));
            return parseInt($$(id).innerHTML);
        }       
        return 0 ;
    }
};
App.utils.onload(Cart.controllers.init);
