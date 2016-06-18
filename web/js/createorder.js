if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.createorder = {
    init: function() {
        $('.buy-by-code-input').focus();
        $(document).on('change', ".order-form input[name='Order[user_id]']", this.findUser);
        $(document).on('keypress', ".order-form input[name='Order[user_id]']", function(e) {
            if(e.which == 13) {
                $(".order-form input[name='Order[user_id]']").change();
                return false;
            }
        });
        
        $(document).on('keypress', function(event) {
            if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))) {
                $("#orderForm").submit();
            }
        });
        
        $(document).on('click', ".render-cart", this.updateCart);
    },
    chooseUser: function(id) {
        $(".order-form input[name='Order[user_id]']").val(id).change();
        $('#usersModal').modal('hide');
    },
    updateCartUrl: '',
    updateCart: function() {
        jQuery.post(pistol88.createorder.updateCartUrl, {},
            function(json) {
                $('.order-form .cart').html(json.cart);
                $('.order-form .total').html(json.total);
            }, "json");

        return true;
    },
    findUser: function() {
        var input = $(this);
        userId = $(this).val();

        if(userId != '') {
            $(input).css('opacity', '0.2');
            $.post($(this).data('info-service'), {userId: userId},
                function(json) {
                    $(input).css('opacity', '1');
                    if(json.status == 'success') {
                        $(".order-form input[name='Order[user_id]']").val(json.id);
                        $(".order-form input[name='Order[email]']").val(json.email);
                        $(".order-form input[name='Order[phone]']").val(json.phone);
                        $(".order-form input[name='Order[client_name]']").val(json.client_name);
                        if(json.promocode) {
                            $(".promo-code-enter input").val(json.promocode).change();
                        }
                    }
                    else {
                        alert(json.message);
                    }
                    
                }, "json");
        }
    }
};

pistol88.createorder.init();
