<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar Sesión</title>

    

        <!-- Plugin scripts -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <script src="<?php echo WEB_ROOT ?>plantilla/vendors/bundle.js"></script>
    <!-- App scripts -->
    <script src="<?php echo WEB_ROOT ?>plantilla/assets/js/app.js"></script>
    <script src="<?php echo WEB_ROOT ?>js/heaven/rollups/aes.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT ?>js/heaven/efectos2.js"></script>
     <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo WEB_ROOT ?>img/logo_empresa.jpeg"/>
    <link href="<?php echo WEB_ROOT ?>plantilla/assets/css/lite-purple.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT ?>css/stylelogin.css">


     <script type="text/javascript" >
      const menu = "<?php echo MENU ?>";
      const web_root = "<?php echo WEB_ROOT ?>";
      const page_root = "<?php echo PAGE_ROOT ?>";

    </script>




</head>

<body>
<div class="container">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>Iniciar Sesión</h3>
                <div class="d-flex justify-content-end social_icon">
                    <!-- <span><i class="fab fa-facebook-square"></i></span>
                    <span><i class="fab fa-google-plus-square"></i></span>
                    <span><i class="fab fa-twitter-square"></i></span> -->
                </div>
            </div>
            <div class="card-body">
                <form id="formulario"  autocomplete="off">
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
                        
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" name="clave" id="clave" class="form-control" placeholder="Ingrese contraseña">
                    </div>

                    <div class="form-group">
                         <label for="email">Código de validación</label>
                         <div class="input-group">
                            <div class="input-group-prepend" style="border: none;">
                            <button class="btn btn-sm btn-outline-primary" type="button" disabled style="background: #fff;padding: 0px"><img src="captcha.php"></button>
                            </div>
                            <input type="text" class="form-control" placeholder="Ingrese codigo de validación" name="validador" id="validador" maxlength="6" required autocomplete="off">
                         </div>
                    </div>
                    <!-- <div class="row align-items-center remember">
                        <input type="checkbox">Remember Me
                    </div> -->
                    <div class="form-group">
                        <!-- <input type="submit" value="Login" class="btn float-right login_btn"> -->
                        <button class="btn float-right login_btn">Ingresar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <!-- <div class="d-flex justify-content-center links">
                    Don't have an account?<a href="#">Sign Up</a>
                </div>
                <div class="d-flex justify-content-center">
                    <a href="#">Forgot your password?</a>
                </div> -->
            </div>
        </div>
    </div>
</div>
</body>

















<!-- <body>
  
  <div class="auth-layout-wrap" style="background-image: url(img/fondo2.jpg)">
    <div class="auth-content">
        <div class="card o-hidden" style="background: linear-gradient(135deg, #fffffffa 50%, #ffffff00 130%);">
            <div class="row">


                <div class="col-md-6">
                    
                    <div class="p-4" id="login">
                       <div class="auth-logo text-center mb-4"><img src="img/logo_empresa.jpeg" alt="" style="width: 100%;height: 100% !important"></div>
                        <h1 class="mb-3 text-18">Iniciar Sesión</h1>
                          <form id="formulario"  autocomplete="off">         
                            <div class="form-group">
                                <label for="email">Usuario</label>
                                <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Ingrese Usuario" required autofocus autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" name="clave" id="clave" class="form-control" placeholder="Ingrese Contraseña" required autocomplete="off">
                            </div>

                            <div class="form-group">
                                 <label for="email">Código de validación</label>
                                 <div class="input-group">
                                    <div class="input-group-prepend" style="border: none;">
                                    <button class="btn btn-sm btn-outline-primary" type="button" disabled style="background: #fff;padding: 0px"><img src="captcha.php"></button>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Ingrese codigo de validación" name="validador" id="validador" maxlength="6" required autocomplete="off">
                                 </div>
                            </div>

                            <button class="btn btn-rounded btn-primary btn-block mt-2">Ingresar</button>
                        </form>
                    </div>

                </div>

                <div class="col-md-6" style="padding:2% 5% 0% 0%;">
                  <img src="img/gesproxxxxx.png" alt="">
                  <div role="alert" aria-live="polite" aria-atomic="true" class="alert alert-dismissible alert-alert alert-card alert-danger"
                  style="padding: 10px;">¡Datos <strong class="text-capitalize">Importantes!</strong> para ingresar al sistema. </div>
                  <ol>
                      <li style="border-bottom:1px solid #278438;margin-bottom: 5px">Se debe ingresar con datos y claves de red sin @anla.gov.co en el usuario</li>
                      <li style="border-bottom:1px solid #278438;margin-bottom: 5px"><b>Acceso Denegado 1:</b> Si el sistema te emite este error, debes validar tus credenciales o realizar el cambio de clave de tu correo institucional porque caduco.</li>
                      <li style="border-bottom:1px solid #278438;margin-bottom: 5px"><b>Acceso Denegado 7:</b>Si el sistema te emite este error, envía una mesa de ayuda con una imagen adjunta explicando el error al ingresar.</li>

                  </ol>
                </div>
               
                <div class="respuesta" style="margin:auto; width: 80%;margin-bottom: 10px"><i class="textomsg"></i></div>

            </div>
        </div>
    </div>
   </div>
   
</body> -->
</html>