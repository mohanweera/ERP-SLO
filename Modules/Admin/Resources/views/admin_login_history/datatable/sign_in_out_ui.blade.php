let sign_in_at = full["sign_in_at"];
let sign_out_at = full["sign_out_at"];
let sign_out_type = full["sign_out_type"];
let login_failed_reason = full["login_failed_reason"];

var uiText = '<div class="clearfix">';

    if(sign_in_at !== null)
    {
        uiText += 'Sign In: '+sign_in_at+'<br>';
    }

    if(sign_out_at !== null)
    {
        uiText += 'Sign Out: '+sign_out_at+'<br>';

        if(sign_out_type == '1')
        {
            uiText += 'Method: Manually<br>';
        }
        else
        {
            uiText += 'Method: Auto<br>';
        }
    }

    if(login_failed_reason !== null)
    {
        uiText += '<div class="btn btn-danger">Login Failed</div><br>';
        uiText += 'Reason:<br>';
        uiText += login_failed_reason+'<br>';
    }
    uiText += '</div>';

return uiText;
