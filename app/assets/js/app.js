require('../css/app.scss');

require('bootstrap');
require('@coreui/coreui');
require('bootstrap-select');

jQuery = require('jquery');

jQuery('#modalDelete').on('show.bs.modal', function (event) {
    var button = jQuery(event.relatedTarget);
    var action = button.data('action');
    jQuery(this).find('.modal-footer form').attr('action', action);
});

jQuery('form').on('submit', function () {
    jQuery('.modal').modal('hide');
    jQuery('#modalLoading').modal('show');
});
