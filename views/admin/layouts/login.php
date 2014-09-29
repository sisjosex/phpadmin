<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="description" content="Administrador 1.0"/>
    <meta name="author" content="sisjosex@gmail.com"/>

    <title><?php echo $title ?></title>

    <link type="text/css" href="<?php echo BASE_URL ?>assets/admin/css/login.min.css" rel="stylesheet">

    <?php echo @$styles ?>
</head>

<body>

<div class="login">
    <div class="login-screen">
        <div id="page" class="page">
            <div class="app-title">
                <h1>Login Access</h1>
            </div>
            <?php echo @$content ?>
        </div>
    </div>
</div>

<?php echo @$scripts ?>

<script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/lib/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/lib/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/js/framework.min.js"></script>
<script type="text/javascript">
    BASE_URL = '<?php echo BASE_URL ?>';
    modules = <?php echo json_encode($modules); ?>;
    language = <?php echo json_encode($language); ?>;

    function frameworkInit() {}
</script>

</body>

</html>