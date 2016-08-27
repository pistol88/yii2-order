if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.oneclick = {
    init: function() {
        $('.pistol88_order_oneclick_form form').on('submit', this.sendOrder)
    },
    sendOrder: function() {
        var form = $(this);
        $(form).css('opacity', '0.3');
        var data = $(form).serialize();
        data = data+'&ajax=1';

        jQuery.post($(form).attr('action'), data,
            function(json) {
                if(json.result == 'success') {
                    $(form).parents('.modal').modal('hide');
                    $(form).find('input,textarea').val('');
                    document.location = json.redirect;
                }
                else {
                    console.log(json.errors);
                    alert(json.errors);
                }
                
                $(form).css('opacity', '1');

                return true;

            }, "json");
            
        return false;
    }
};

pistol88.oneclick.init();
