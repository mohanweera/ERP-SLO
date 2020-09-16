let country = full["country"];
let city = full["city"];

var uiText = '<div class="clearfix">';

    if(country !== null)
    {
        uiText += country.country_name+"<br>";
        uiText += 'City: '+city;
    }
    uiText += '</div>';

return uiText;
