<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/build/css/app.css">
    <title>Sisani</title>
</head>
<body class="inicio">
    <main>
        <section>
            <h1>Iniciar Sesión</h1>
            <form>
                <div class="user">
                    <label for="user">Usuario</label>
                    <input type="text" name="user" id="user" placeholder="Usuario">
                </div>
                <div class="pass">
                    <label for="pass">Contraseña</label>
                    <input type="text" name="pass" id="pass" placeholder="Contraseña">
                </div>
                <a href="/">Olvido Contraseña</a>
                <div class="iniciar">
                    <button>
                        <ion-icon name="enter-outline" class="size3"></ion-icon>
                        <input type="submit" value="Iniciar Sesión">
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

<script src="/build/css/app.css"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>