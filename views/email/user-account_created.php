<html>
<head><title>:: <?php echo $title ?> ::</title></head>

<body>

<div style="margin-top:0;">
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
</div>

<div style="margin-top:20px !important; margin-left:12%;">

    <p>

    <h1 style="font-size:20px; font-family:Arial; font-weight:bolder;line-height:34pt; color:black !important;">
    <span style="color:black;">Hola <?php echo $admin['first_name'] . ' ' . $admin['last_name'] ?>!</span><br>
    <span style="font-weight:normal; color:black;">Tu informacion de cuenta para el Sistema de Expensas esta listo!</span></br>
    <span style="font-weight:normal; color:black;">Puedes ingresar y empezar a usarlo.</span><br><br>
    <span style="color:black;">Detalles de la cuenta:</span><br>
    <span style="color:black;">Nombre de usuario:</span><span
        style="font-family:Arial; font-size:21px; font-weight:normal;color:black;"> <?php echo $admin['email'] ?></span><br>
    <span style="color:black;">Contrase&ntilde;a:</span><span
        style="font-family:Arial; font-size:21px; font-weight:normal;color:black;"> <?php echo $admin['password'] ?></span><br><br>

    <span style="color:black;">Area del sistema: </span><span
        style="font-size:20px; font-family:Arial; font-weight:normal;"><a
            href="<?php echo BASE_URL ?>"><?php echo BASE_URL ?></a></span><br><br>
        </span>
    </h1>
    </p>
</div>


</div>
</body>
</html>

