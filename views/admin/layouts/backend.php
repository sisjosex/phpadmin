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

    <!-- Styles -->
    <link type="text/css" href="<?php echo BASE_URL ?>assets/admin/css/style.min.css" rel="stylesheet">

    <!-- Uploader -->
    <link type="text/css" href="<?php echo BASE_URL ?>assets/admin/lib/dropzone/dropzone.min.css" rel="stylesheet">

    <!-- Date Range picker -->
    <link type="text/css" href="<?php echo BASE_URL ?>assets/admin/lib/daterangepicker/daterangepicker.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="<?php echo BASE_URL ?>assets/admin/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <link href="<?php echo BASE_URL ?>assets/admin/lib/font-awesome/css/font-awesome-ie7.min.css" rel="stylesheet" type="text/css">
    <![endif]-->

    <link href="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/css/froala_editor.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/css/froala_reset.min.css" rel="stylesheet" type="text/css">

    <!-- Jquery -->
    <script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/lib/jquery-1.11.0.min.js"></script>

    <!-- Validation -->
    <script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/lib/jquery-validation/jquery.validate.min.js"></script>

    <!-- Date Range picker -->
    <script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/lib/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/lib/daterangepicker/jquery.daterangepicker.min.js"></script>

    <!-- Uploader -->
    <script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/lib/dropzone/dropzone.min.js"></script>

    <!-- Froala Editor -->
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/froala_editor.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/libs/beautify/beautify-html.js"></script>
    <!--[if lt IE 9]>
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/froala_editor_ie8.min.js"></script>
    <![endif]-->
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/plugins/tables.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/plugins/colors.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/plugins/fonts/fonts.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/plugins/fonts/font_family.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/plugins/fonts/font_size.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/plugins/block_styles.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/admin/lib/froala_editor/js/plugins/video.min.js"></script>

    <!-- Pagination -->
    <link href="<?php echo BASE_URL ?>assets/admin/lib/simplePagination/simplePagination.min.css" rel="stylesheet" type="text/css">
    <script src="<?php echo BASE_URL ?>assets/admin/lib/simplePagination/simplePagination.min.js"></script>

    <!-- Scrollbar -->
    <link href="<?php echo BASE_URL ?>assets/admin/lib/scrollbar/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css">
    <script src="<?php echo BASE_URL ?>assets/admin/lib/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- Google Maps -->
    <script>
    function initialize() {}
    function loadScripts() {
    var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
        'callback=initialize';
        document.body.appendChild(script);
    }
    </script>
    <!--<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=true"></script>-->

    <!-- Framework -->
    <script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/js/framework.js"></script>
    <?php echo @$styles ?>
</head>

<body onload="loadScripts()">

<div id="page-container" class="container">
    <div class="page">
        <div class="header">
            <div class="logo">
                
            </div>
            <div id="settings-form"></div>
            <div class="right">
                <?php echo Auth::$user['first_name'] . ' ' . Auth::$user['last_name'] ?>
                <a class="btn" href="<?php echo BASE_URL ?>admin/login/logout"><?php echo lang('Logout') ?></a>
            </div>
        </div>

        <div class="sidebar">
            <div class="header">
                <h3 class="title">
                    Dashboard
                </h3>
            </div>
            <ul id="sidebar" class="menu"></ul>
        </div>
        <div id="page" class="module-container">
            <?php echo @$content ?>
            <div id="statistic"></div>
        </div>
    </div>
</div>

<?php echo @$scripts ?>
<script type="text/javascript">

    $(function(){
        $(".sidebar").mCustomScrollbar({theme:"minimal"});
    });

    BASE_URL = '<?php echo BASE_URL ?>admin/';
    modules = <?php echo json_encode($modules); ?>;
    sidebar = <?php echo json_encode($sidebar); ?>;
    language = <?php echo json_encode($language); ?>;

    function frameworkInit() {
        <?php if(Auth::$user['role'] == 'sadmin') { ?>

            moduleManager.modules.user.containers.grid.element.show();
            moduleManager.modules.user.containers.grid.refresh();
            $('#sidebar .menu[rel=user]').addClass('active');

        <?php } ?>
    }

</script>

<script type="text/javascript" src="<?php echo BASE_URL ?>assets/admin/lib/chart/Chart.min.js"></script>
</body>

</html>