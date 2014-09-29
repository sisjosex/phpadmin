<!DOCTYPE html>
<html lang="es">
<head>
    <title><?php echo $title ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="Short Description">

    <!--
    <meta content="index, follow" name="robots">
    -->

    <!-- For facebook Share
    <meta property="og:title" content="<?php echo $title ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="Short Description."/>
    <meta property="og:url" content="<?php echo BASE_URL ?>"/>
    <meta property="og:image" content="<?php echo BASE_URL ?>assets/frontend/img/logo.png"/>
    <meta property="og:site_name" content="..."/>
    -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">

    <link rel="shortcut icon" href="<?php echo BASE_URL ?>assets/frontend/img/favicon.ico"/>

    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/frontend/css/style.css"/>
    <script type="text/javascript" src="<?php echo BASE_URL ?>assets/frontend/lib/jquery-1.11.0.min.js"></script>

    <script>
        $(function () {


        });
    </script>

</head>
<body>


<h3>PHPAdmin - Frontend page test</h3>


<script>
    jQuery.preloadImages = function () {
        for (var i = 0; i < arguments.length; i++) {
            jQuery("<img>").attr("src", arguments[i]);
        }
    }

    $.preloadImages(
        "<?php echo BASE_URL ?>assets/frontend/img/img1.png",
        "<?php echo BASE_URL ?>assets/frontend/img/img2.png", //...
    );
</script>
</body>
</html>
