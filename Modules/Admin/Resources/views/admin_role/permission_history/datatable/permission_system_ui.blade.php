
let system = full["permission_system"];

let uiText = '<div class="clearfix text-center mb-2">';
    uiText += '<a href="<?php echo $systemUrl; ?>/'+system.id+'" class="text-white">';
    uiText += '<div class="btn btn-info">'+system.system_name+'</div>';
        uiText += "</a>";
    uiText += '</div>';

return uiText;

