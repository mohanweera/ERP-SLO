<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>KIU ERP | Dashboard</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <style type="text/css">
        /*---Notification Styles---*/
        .notifications {
            margin: auto;
            display:flex;
            justify-content:center;
            align-items:center;
            width:90%;
            max-width:90% !important;
            height:auto;
            position: fixed;
            top:18px;
            left:5%;
            z-index: 999999;
            display:none;
        }

        .notifications-content {
            clear:both !important;
            float:none !important;
            width:500px;
            max-width:100% !important;
            height:auto;
            margin:0 auto !important;
            padding:20px;
            box-shadow:0 3px 5px #ababab;
            font-weight:bold;
            position:relative;
            border:none;
        }

        .notifications-content.alert-warning {
            background-color: #f3c022;
            color: #fff;
        }

        .notifications-content.alert-failed {
            background-color: #FF6C60;
            color: #fff;
        }

        .notifications-content.alert-success {
            background-color: #1ca59e;
            color: #fff;
        }

        .notifications-content .notifications-close {
            position:absolute;
            top:-15px;
            right:-15px;
            width:30px;
            height:30px;
            border-radius:100%;
            color:#ffffff;
            background-color:inherit;
            text-align:center;
            font-size:13px;
            border:none;
            outline:none !important;
        }
        .notifications-content .notifications-close:hover {
            box-shadow:0 3px 5px #ababab;
        }
        /*---Notification Styles---*/
    </style>
</head>

<body class="hold-transition login-page">

<?php
$notifications = [];
$response = session("response");
if($response)
{
    if(isset($response["notify"]))
    {
        $notifications = $response["notify"];
    }
}

if(empty($notifications))
{
    $notify = session("notify");

    if($notify)
    {
        $notifications = $notify;
    }
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    //Notifications javascript
    window.showNotifications = function(data)
    {
        if(data.status && data.notify && data.notify.length > 0)
        {
            let status=data.status;
            let notify=data.notify;

            let errorText="";
            $(notify).each(function(index, text){

                errorText+="<span class='glyphicon glyphicon-hand-right'></span> "+text+"<br/>";
            });

            if($("#notifications"))
            {
                $("#notifications").remove();
            }

            let errorContainer="";

            errorContainer+='<div class="notifications" id="notifications">';
            errorContainer+='<div class="notifications-content alert alert-'+status+'">';
            errorContainer+='<button type="button" class="close notifications-close">&times;</button>';
            errorContainer+=errorText;
            errorContainer+='</div>';
            errorContainer+='</div>';

            $("body").append(errorContainer);
            $("#notifications").fadeIn(300);

            $("#notifications").find(".notifications-close").click(function(){
                $("#notifications").fadeOut(300, function(){
                    $("#notifications").remove();
                });
            });

            let timeout = window.setTimeout(function(){

                $("#notifications").remove();

                window.clearTimeout(timeout);
            }, 10000);
        }
    }
    showNotifications(<?php echo json_encode($notifications); ?>);
</script>
@yield('content')

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>
