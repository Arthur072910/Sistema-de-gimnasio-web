<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/admin.css">
  </head>
  <body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-area">
                <img src="../assets/img/ChatGPT Image 30 ene 2026, 10_35_11 p.m..png" alt="" srcset="" style="height: 100px;">
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
                        Configuración
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="search-area">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Buscar...">
                    </div>
                </div>
                <div class="admin-profile">
                    <i class="fas fa-bell"></i>
                    <div class="admin-avatar">AD</div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Miembros Activos</h4>
                        <h2>1,284</h2>
                        <span>↑ +12% esta semana</span>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Clases Hoy</h4>
                        <h2>24</h2>
                        <span>8 en progreso</span>
                    </div>
                    <div class="stat-icon pink">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Ingresos Mensuales</h4>
                        <h2>$45.2K</h2>
                        <span>↑ +8% vs mes anterior</span>
                    </div>
                    <div class="stat-icon green">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Nuevos Miembros</h4>
                        <h2>156</h2>
                        <span>↑ +23 este mes</span>
                    </div>
                    <div class="stat-icon blue">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-container">
                    <div class="chart-header">
                        <h3 class="chart-title">Asistencia Semanal</h3>
                        <select class="chart-select">
                            <option>Última semana</option>
                            <option>Último mes</option>
                            <option>Último año</option>
                        </select>
                    </div>
                    
                    <div class="chart-bars">
                        <div class="bar-item">
                            <div class="bar" style="height: 120px;"></div>
                            <span>Lun</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 150px;"></div>
                            <span>Mar</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 180px;"></div>
                            <span>Mié</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 200px;"></div>
                            <span>Jue</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 170px;"></div>
                            <span>Vie</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 110px;"></div>
                            <span>Sáb</span>
                        </div>
                        <div class="bar-item">
                            <div class="bar" style="height: 90px;"></div>
                            <span>Dom</span>
                        </div>
                    </div>
                </div>

                <div class="membership-stats">
                    <h3 class="chart-title">Membresías</h3>
                    
                    <div class="membership-item">
                        <div class="membership-label">
                            <span>Premium</span>
                            <span class="percentage">45%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress pink" style="width: 45%;"></div>
                        </div>
                    </div>
                    
                    <div class="membership-item">
                        <div class="membership-label">
                            <span>Estándar</span>
                            <span class="percentage">35%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress purple" style="width: 35%;"></div>
                        </div>
                    </div>
                    
                    <div class="membership-item">
                        <div class="membership-label">
                            <span>Básico</span>
                            <span class="percentage">20%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress green" style="width: 20%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classes Table -->
            <div class="classes-section">
                <div class="section-header">
                    <h3 class="chart-title">Clases del Día</h3>
                    <button class="btn-add">
                        <i class="fas fa-plus"></i>
                        Agregar Clase
                    </button>
                </div>

                <table class="classes-table">
                    <thead>
                        <tr>
                            <th>Clase</th>
                            <th>Instructor</th>
                            <th>Horario</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CrossFit</td>
                            <td>Carlos Rodríguez</td>
                            <td>08:00 - 09:00</td>
                            <td>25/30</td>
                            <td><span class="class-status status-active">Activa</span></td>
                        </tr>
                        <tr>
                            <td>Yoga</td>
                            <td>María González</td>
                            <td>10:00 - 11:00</td>
                            <td>20/20</td>
                            <td><span class="class-status status-full">Llena</span></td>
                        </tr>
                        <tr>
                            <td>Spinning</td>
                            <td>Juan Pérez</td>
                            <td>17:00 - 18:00</td>
                            <td>15/20</td>
                            <td><span class="class-status status-active">Activa</span></td>
                        </tr>
                        <tr>
                            <td>Zumba</td>
                            <td>Ana Martínez</td>
                            <td>19:00 - 20:00</td>
                            <td>18/25</td>
                            <td><span class="class-status status-active">Activa</span></td>
                        </tr>
                        <tr>
                            <td>Boxeo</td>
                            <td>Pedro Sánchez</td>
                            <td>20:00 - 21:00</td>
                            <td>12/20</td>
                            <td><span class="class-status status-cancelled">Cancelada</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Recent Activity -->
            <div class="activity-section">
                <div class="section-header">
                    <h3 class="chart-title">Actividad Reciente</h3>
                    <a href="#" class="view-all">Ver todo</a>
                </div>
                
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-details">
                            <p><strong>Laura Méndez</strong> se unió al gimnasio</p>
                            <span class="activity-time">Hace 10 minutos</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="activity-details">
                            <p><strong>Pago recibido</strong> de Roberto Sánchez</p>
                            <span class="activity-time">Hace 25 minutos</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="activity-details">
                            <p><strong>Clase de CrossFit</strong> comenzó</p>
                            <span class="activity-time">Hace 45 minutos</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="activity-details">
                            <p><strong>Ana Torres</strong> alcanzó 100 sesiones</p>
                            <span class="activity-time">Hace 1 hora</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="footer-area">
    <footer class="footer text-center text-lg-start">

      <div class="brand">
        <span>© 2024 deluxgym</span>
        <a href="#" class="ml-2">deluxgym.com</a>
      </div>

      <div class="links">
        <a href="#">Inicio</a>
        <a href="">Catálogo</a>
        <a href="view/contactos.php">Contactos</a>
      </div>

      <div class="redes-area">
        <a class="redes-img" href="#"><img src="../assets/img/facebook (1).png" alt="Facebook"></a>
        <a class="redes-img" href="#"><img src="../assets/img/instagram.png" alt="Instagram"></a>
      </div>

      <div class="copyright">
        Diseñado por deluxgym
      </div>

    </footer>
  </div>

      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>