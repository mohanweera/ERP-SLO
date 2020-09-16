var id = full["id"];

var uiText = '<div class="clearfix text-center">';
        uiText += '<a href="<?php echo $permissionUrl; ?>/'+id+'" class="text-white">';
            uiText += '<div class="btn btn-info">Group Permissions</div>';
        uiText += "</a>";
    uiText += '</div>';

return uiText;
