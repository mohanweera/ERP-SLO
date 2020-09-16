var admin = full["admin"];
var admin_id = full["admin_id"];
var uiText = '<div class="clearfix">';

    if(admin !== null)
    {
        <?php
        if(isset($adminUrl) && $adminUrl != "")
        {
            ?>
            uiText += '<a href="<?php echo $adminUrl; ?>/'+admin_id+'">';
                uiText += admin.name;
            uiText += '</a>';
            <?php
        }
        else
        {
            ?>
            uiText += admin.name;
            <?php
        }
        ?>
    }
    uiText += '</div>';

return uiText;
