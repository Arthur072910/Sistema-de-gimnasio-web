$(document).ready(function() {
    
    $('#campanaNotificacion').popover({
        trigger: 'click', 
        html: true,       
        sanitize: false   
    });

  
    $('body').on('click', function (e) {
        if ($(e.target).data('toggle') !== 'popover' 
            && $(e.target).parents('.popover.show').length === 0) { 
            $('#campanaNotificacion').popover('hide');
        }
    });
});