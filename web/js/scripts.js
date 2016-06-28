if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.order = {
    init: function() {
        $(document).on('change', ".order-widget-shipping-type select", this.updateShippingType);
    },
    updateCartUrl: '',
    updateShippingType: function() {
        jQuery.post(pistol88.order.updateShippingType, {shipping_type_id: $(this).val()},
            function(json) {
                $('.pistol88-cart-price>span').html(json.total);
            }, "json");

        return true;
    },
};

pistol88.order.init();
