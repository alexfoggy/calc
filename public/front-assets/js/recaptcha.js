function getRecaptcha(action_name, where_name) {
    grecaptcha.ready(function() {
        grecaptcha.execute('6LfqKg4dAAAAAJSZjUKE3uXv2PWOwREW196VmrEo', {action: action_name}).then(function(token) {
            $('#'+where_name).val(token);
        });
    });
}