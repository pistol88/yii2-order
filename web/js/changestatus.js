if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.changestatus = {
    csrf: null,
    csrf_param: null,
    init: function() {
        pistol88.changestatus.csrf = jQuery('meta[name=csrf-token]').attr("content");
        pistol88.changestatus.csrf_param = jQuery('meta[name=csrf-param]').attr("content");
        $(document).on('change', ".pistol88-change-order-status", this.changeStatus);
    },
    changeStatus: function() {
        var link = $(this);
        $(link).css('opacity', '0.2');
        
        data = {};
        data['status'] = $(this).val();
        data['id'] = $(this).data('id');
        data[pistol88.changestatus.csrf_param] = pistol88.changestatus.csrf;

        jQuery.post($(this).data('link'), data,
            function(json) {
                if(json.result == 'success') {
                    $(link).css('opacity', '1');
                }
                else {
                    console.log(json.error);
                }

            }, "json");
        
        return false;
    },
};

pistol88.changestatus.init();
