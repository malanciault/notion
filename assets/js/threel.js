function doPost(uri, params, successCallback=false, callbackParams=false) {
    $.ajax({
        type: "POST",
        url: siteUrl + uri,
        dataType: 'json',
        data: params,
        success: function (result) {
            if (successCallback) {
                var fn = window[successCallback];
                fn(result, callbackParams);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {

        }
    });
}
function showFeedback(text, type='success', title='', ) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
    toastr[type](text, title);
}

function showRetro(text, type='success', title='', ) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-bottom-left",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": 0,
        "hideDuration": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr[type](text, title);
}


// Tooltips Initialization
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})