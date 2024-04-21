<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
    crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <?php include '../partials/navbar.php'; ?>
  <?php include '../partials/sidebar.php'; ?>

  <div id="app">
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard v1</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3" v-for="data in resumeCardData" :key="data.id">
              <div class="info-box mb-3">
                <span :class="data.subBoxStyle"> <i :class="data.icon"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">{{data.title}}</span>
                  <span class="info-box-number">{{data.number}}</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </div>
          <!-- /.row -->

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
              <!-- Custom tabs (Charts with tabs)-->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title" style='font-weight:600'>
                    PERFORMANCE DU MOIS
                  </h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6" v-for="data in statCardData" :key="data.id">
                      <div class="container info-box ">
                        <div class="row" style="width:100%">
                          <div class="col-md-6">
                            <div class="info-box-content">
                              <span class="info-box-number theme-text-primary theme-stat-size" style="ont-size:13px">
                                {{data.number}}
                              </span>
                              <span class="info-box-text">{{data.title}}</span>
                            </div>
                          </div>
                          <div class="col-md-6 align-right">
                            <div>
                              <span :class="data.subBoxStyle"> 250% </span>
                              </br>
                              <p style="color: black; font-size:13px"> {{data.activeUser}} {{data.title}} actif </p>
                            </div>
                          </div>
                        </div>


                      </div>
                    </div>


                  </div><!-- /.card-body -->
                </div>
                <!-- /.card -->


                <!-- /.card -->
            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">

              <!-- Map card -->
              <div class="card ">

                <div class="card-header">
                  <h3 class="card-title">
                    Dernières transactions
                  </h3>
                </div>

                <div class="card-body">
                  <div class="container info-box" v-for="data in dataList" :key="data.id">
                    <div class="row" style="width: 100%,">
                      <div class="col-md-6">
                        {{ data.title }}
                      </div>
                      <div class="col-md-6">
                        un test ici 
                      </div>
                    </div>
                    <br>

                    <div class="row">
                      <div class="col-md-6">
                         <p style="color:black">DE : {{data.from}} </p>
                         <p style="color:black">A : {{data.to}} </p>
                      </div>
                      <div class="col-md-6">
                        {{ data.title }}
                      </div>
                    </div>

                  </div>


                </div>

              </div>

              <div class="card-footer bg-transparent" style="height: 300px">

                <!-- /.row -->
              </div>
          </div>
          <!-- /.card -->

          <!-- /.card -->
      </section>
      <!-- right col -->
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
  </div>
  </section>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.4
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <script src="../plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="../plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="../plugins/moment/moment.min.js"></script>
  <script src="../plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="../plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="../dist/js/pages/dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../dist/js/demo.js"></script>


  <script>
    // Initialiser une nouvelle instance de Vue
    new Vue({
      el: '#app',
      data: {
        loggedIn: false,
        username: '',
        password: '',
        dataList: [
          { id: 1, title: 'Donnée 1', from :'+228 98489705', to:'+228 90050505' },
          { id: 2, title: 'Donnée 2' , from :'+228 98489705', to:'+228 90050505' },
          { id: 3, title: 'Donnée 3', from :'+228 98489705', to:'+228 90050505' },
        ],
        resumeCardData: [
          { id: 1, title: 'Total Clients', number: '5900', icon: 'fas fa-users', subBoxStyle: 'info-box-icon  elevation-1 bg-success' },
          { id: 2, title: 'Total Marchands', number: '900', icon: 'fas fa-store', subBoxStyle: 'info-box-icon  elevation-1 bg-warning ' },
          { id: 3, title: 'Total Chauffeurs', number: '500', icon: 'fas fa-car', subBoxStyle: 'info-box-icon  elevation-1 bg-primary ' },
          { id: 4, title: 'Total Transactions', number: '30900', icon: 'fas fa-exchange-alt', subBoxStyle: 'info-box-icon  elevation-1 bg-danger ' },
        ],
        statCardData: [
          { id: 1, title: 'Client', number: '5000', subBoxStyle: 'bg-info elevation-1 stat-info', activeUser: '50' },
          { id: 2, title: 'Marchand', number: '200', subBoxStyle: 'bg-danger elevation-1 stat-info', activeUser: '20' },
          { id: 3, title: 'Chauffeur', number: '30', subBoxStyle: 'bg-warning elevation-1 stat-info', activeUser: '90' },
        ],
      },

      methods: {
        login() {
          // Ici, vous pouvez implémenter la logique d'authentification, par exemple en utilisant une requête AJAX vers un backend
          // Pour cet exemple, nous définissons juste loggedIn à true si le nom d'utilisateur et le mot de passe sont remplis
          if (this.username && this.password) {
            this.loggedIn = true;

            window.location.href = './screens/dashboard.php';
          } else {
            alert('Veuillez remplir tous les champs.');
          }
        },
        logout() {
          this.loggedIn = false;
          this.username = '';
          this.password = '';
        }
      }
    });
  </script>
</body>

</html>