jQuery(function($){
    // Toggle between Customer and Provider
    $('input[name="user_type"]').change(function(){
        if($(this).val() === 'customer'){
            $('#customer-fields').show();
            $('#provider-fields').hide();
        } else {
            $('#customer-fields').hide();
            $('#provider-fields').show();
        }
    });
    // Limit interests selection to 5
    $('.checkbox-group input[type="checkbox"]').on('change', function() {
        var max = 5;
        if ($('.checkbox-group input[type="checkbox"]:checked').length > max) {
            this.checked = false;
            alert('You can select up to ' + max + ' interests only.');
        }
    });
}); 