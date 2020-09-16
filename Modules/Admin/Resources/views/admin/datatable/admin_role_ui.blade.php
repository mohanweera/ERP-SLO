var admin_role = full["admin_role"];
var admin_role_id = full["admin_role_id"];
var uiText = '<div class="clearfix">';

    if(admin_role !== null)
    {
    <?php
    if(isset($url) && $url != "")
    {
        ?>
        uiText += '<a href="<?php echo $url; ?>'+admin_role_id+'">';
            uiText += admin_role.role_name;
        uiText += '</a>';
        <?php
    }
    else
    {
        ?>
        uiText += admin_role.role_name;
        <?php
    }
    ?>
    }
    uiText += '</div>';

return uiText;
