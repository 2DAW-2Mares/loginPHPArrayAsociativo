<?php
$dbname = "usuarios";
$user = "root";
$password = "alumno";
try {
    $dsn = "mysql:host=localhost;dbname=$dbname";
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e){
    echo $e->getMessage();
}


if (isset($_POST['usuarios'])) {
    $usuarios = $_POST['usuarios'];
}

if (isset($_POST['username'])) {
    $usuarios[htmlspecialchars($_POST['username'])] = htmlspecialchars($_POST['password']);


    // Prepare
    $stmt = $dbh->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
    // Bind
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $password);
    // Excecute
    $stmt->execute();

}
if (isset($_POST['login'])) {
    
    // FETCH_ASSOC
        $stmt = $dbh->prepare("SELECT * FROM usuarios WHERE username = ? AND password = ?");
        // Especificamos el fetch mode antes de llamar a fetch()
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        // Asignamos valores a los parámetros
        $username = htmlspecialchars($_POST['login']);
        $password = htmlspecialchars($_POST['password']);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
            // Ejecutamos
        $stmt->execute();
        // Mostramos los resultados
        if ($row = $stmt->fetch()) : ?>
            Usted se ha autenticado con las siguientes credenciales: <br>
            Usuario: <?php echo $row["username"] ?><br>
            Password: <?= $row["password"] ?><br><br>
        <?php else : ?>
            Algo ha fallado en el login
        <?php endif; 
    
    $loginCorrecto = $usuarios[$_POST['login']] === htmlspecialchars($_POST['password']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon"
          href="https://production-assets.codepen.io/assets/favicon/favicon-8ea04875e70c4b0bb41da869e81236e54394d63638a1ef12fa558a4a835f1164.ico"/>
    <link rel="mask-icon" type=""
          href="https://production-assets.codepen.io/assets/favicon/logo-pin-f2d2b6d2c61838f7e76325261b7195c27224080bc099486ddd6dccb469b8e8e6.svg"
          color="#111"/>
    <title>CodePen - Flat HTML5/CSS3 Login Form</title>


    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto:300);

        .login-page {
            width: 360px;
            padding: 8% 0 0;
            margin: auto;
        }

        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 360px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }

        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #4CAF50;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
        }

        .form button:hover, .form button:active, .form button:focus {
            background: #43A047;
        }

        .form .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
        }

        .form .message a {
            color: #4CAF50;
            text-decoration: none;
        }

        .form .register-form {

        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 300px;
            margin: 0 auto;
        }

        .container:before, .container:after {
            content: "";
            display: block;
            clear: both;
        }

        .container .info {
            margin: 50px auto;
            text-align: center;
        }

        .container .info h1 {
            margin: 0 0 15px;
            padding: 0;
            font-size: 36px;
            font-weight: 300;
            color: #1a1a1a;
        }

        .container .info span {
            color: #4d4d4d;
            font-size: 12px;
        }

        .container .info span a {
            color: #000000;
            text-decoration: none;
        }

        .container .info span .fa {
            color: #EF3B3A;
        }

        body {
            background: #76b852; /* fallback for old browsers */
            background: -webkit-linear-gradient(right, #76b852, #8DC26F);
            background: -moz-linear-gradient(right, #76b852, #8DC26F);
            background: -o-linear-gradient(right, #76b852, #8DC26F);
            background: linear-gradient(to left, #76b852, #8DC26F);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>

    <script>
        window.console = window.console || function (t) {
        };
    </script>


    <script>
        if (document.location.search.match(/type=embed/gi)) {
            window.parent.postMessage("resize", "*");
        }
    </script>


</head>

<body translate="no">

<?php if(isset($loginCorrecto)) : ?>
    <?php if($loginCorrecto) : ?>
            <p>Login correcto
        <?php else :?>
            <p>Login incorrecto
    <?php endif; ?>
<?php endif; ?>
<div class="login-page">
    <div class="form">
        <form class="register-form" method="post">
            <input type="text" name="username" placeholder="username"/>
            <input type="password" name="password" placeholder="password"/>
            <?php
                if(isset($usuarios)) :
                    foreach ($usuarios as $username => $password) :
            ?>
                <input type="hidden" name="usuarios[<?php echo $username ?>]" value="<?php echo $password ?>"/>
            <?php
                    endforeach;
                endif;
            ?>
            <?php
            // FETCH_ASSOC
                $stmt = $dbh->prepare("SELECT * FROM usuarios");
                // Especificamos el fetch mode antes de llamar a fetch()
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                // Ejecutamos
                $stmt->execute();
                // Mostramos los resultados
                while ($row = $stmt->fetch()){
                    echo "Usuario: {$row["username"]} <br>";
                    echo "Password: {$row["password"]} <br><br>";
                }
            ?>
            <input type="submit" value="registrar"/>
        </form>
        <form class="login-form" method="post">
            <input type="text" name="login" placeholder="username"/>
            <input type="password" name="password" placeholder="password"/>
            <?php
            if(isset($usuarios)) :
                foreach ($usuarios as $username => $password) :
                    ?>
                    <input type="hidden" name="usuarios[<?php echo $username ?>]" value="<?php echo $password ?>"/>
                <?php
                endforeach;
            endif;
            ?>
            <input type="submit" value="login"/>
            <p class="message">Not registered? <a href="#">Create an account</a></p>
        </form>
    </div>
</div>
<script src="//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js"></script>

<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>


<script>
    $('.message a').click(function () {
        $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
    });
    //# sourceURL=pen.js
</script>


</body>

</html>
 