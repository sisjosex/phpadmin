<html>
<head><title>:: <?php echo $title ?> ::</title></head>

<body>



    <div
        style="padding-top:50px; align:center !important; top:0px; visibility:visible; z-index:1;  width: 100%; background-color:#f9f9f8;">
        <img width="338" src="<?php echo BASE_URL ?>assets/admin/img/logo.png" alt="logo" align="center" style="
				display: block;
				width: 338px;
				margin: 0 auto;
			">
    </div>
    <div
        style="top:153px; background-image: url('<?php echo BASE_URL ?>assets/img/line.png'); background-repeat: repeat-x; visibility:visible; z-index:2; width:100%;height: 10px;">
    </div>
    <div style="margin-top:20px !important; margin-left:12%; ">

        <h1 style="font-size:24px; font-family:Arial; font-weight:bolder;line-height:37pt; color:black !important;">
            Hello <?php echo $admin['first_name'] ?> <?php echo $admin['last_name'] ?>!
        </h1>

        <h1 style="font-size:24px; font-family:Arial; font-weight:bolder;line-height:37pt; color:black !important;">Parece que olvidaste tu contrase&ntilde;a!</h1>

        <h1 style="font-size:24px; font-family:Arial; font-weight:bolder;line-height:37pt; color:black !important;">No te preocupes.</h1>

        <h1 style="font-size:24px; font-family:Arial; font-weight:bolder;line-height:37pt; color:black !important;">
            Tu nueva contrase&ntilde;a es: <?php echo $admin['password'] ?><br>
        </h1>

    </div>
</body>
</html>

