if (typeof halumein == "undefined" || !halumein) {
    var halumein = {};
}

$(document).on('click', '#order-form-light-submit', function(e) {
    halumein.orderFormLight.sendData(e);
});

halumein.orderFormLight = {
    init : function() {
        // console.log('order form light init');
    },

    sendData: function(e) {
        e.preventDefault();

        var $form = $('[data-role=order-form]'),
            csrfToken = $form.find('[name="_csrf"]').val(),
            sendUrl = $form.attr('action'),
            useAjax = $form.data('ajax');
        if (useAjax === true) {
            var serializedFormData = $form.serialize();
            $.ajax({
                type : 'POST',
                url : sendUrl,
                data : serializedFormData,
                success : function(response) {
                    if (response.status === 'success' && typeof response.nextStep != 'undefined' && response.nextStep != false) {
                        $form.parent().animate({width:'toggle'},350);
                        $form.parent().parent().load(response.nextStep);
                    } else {
                        pistol88.service.clearServiceOrder();
                    }
                },
                fail : function() {
                    alert('ошибка при отправке данных');
                    console.log('fail');
                }
            });
        } else {
            $form.submit();
        }
        return false;
    }
}
