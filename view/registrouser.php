<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/registrouser.css">
  </head>
  <body>

   

    <div class="row">
    <div class="col-sm-3">
        <div class="sidebar">
            <div class="logo-area">
                <img src="../assets/img/logo_deluxGym.png" alt="" srcset="" style="height: 100px;">
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="fas fa-chart-pie"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../view/registrouser.php" class="nav-link">
                        <i class="fas fa-users"></i>
                        Registrar nuevos usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        Miembros
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        Clases
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-credit-card"></i>
                        Pagos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../view/entrenadores.php" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        Registro de entrenadores
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        Horarios
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        Planes
                    </a>
                </li>
                    <a href="../view/reportes.php" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        Reportes
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-sm-9 mt-5">
         <div class="main-content">
             <h3 class="chart-title">Registros de Usuario</h3>
            <div class="container">
            <form action="" class="mt-5" method="post">
              <div class="form-row">
                <input name="nombre" type="text" placeholder="Nombre" class="input-inquietud" />
                <input name="apellido" type="text" placeholder="Apellido" class="input-inquietud" />
                <input name="correo" type="email" placeholder="Correo electrónico" class="input-inquietud" />
                <input name="telefono" type="tel" placeholder="Teléfono" class="input-inquietud" />
                <input name="nacimiento" type="date" placeholder="Fecha de nacimiento" class="input-inquietud full" />
                <select name="genero" class="input-inquietud full">
                  <option value="">Género</option>
                  <option value="m">Masculino</option>
                  <option value="f">Femenino</option>
                </select>
                <input name="password" type="password" placeholder="Contraseña" class="input-inquietud full" />
                <div class="full" style="text-align:right;">
                  <button type="submit" class="btn-submit">Enviar</button>
                </div>
              </div>
            </form>
            </div>
        </div>
    </div>
   
    </div>
   
  
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>