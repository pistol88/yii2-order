if (typeof prologgg == "undefined" || !prologgg) {
    var prologgg = {};
}

prologgg.changestatus = {

    init: function () {
        $(document).on('change', ".pistol88-change-order-status", this.changeStatus);
    },
    changeStatus: function () {
        let modalOperatorka = window.parent.document.getElementById('operatorkaModal');
        jQuery(modalOperatorka).find('.close').click();
        return false;
    },
};

prologgg.changestatus.init();
