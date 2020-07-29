<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white  navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link " data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars elevation-3" style="background-color: #f0f0f5;"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="home.php" class="nav-link ">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contato</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link"><div id="relogio"></div> </a>
            </li>
        </ul>



        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments brand-image img-circle elevation-3" style="background-color: #f0f0f5;"></i>
                    <span class="badge badge-danger navbar-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">--</span>
                    <div class="dropdown-divider"></div>
                    
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">Em Construção</a>
                </div>
            </li>
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell brand-image img-circle elevation-3" style="background-color: #f0f0f5;"></i>
                    <span class="badge badge-warning navbar-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">--</span>
                    <div class="dropdown-divider"></div>

                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer ">Em Construção</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" id="help" data-slide="true" href="#" role="button">
                    <i class="fas fa-question brand-image img-circle elevation-3" style="background-color: #f0f0f5;"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar"  data-slide="true" href="#" role="button">
                   <i class="fas fa-info brand-image img-circle elevation-3" style="background-color: #f0f0f5;"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " data-widget="control-sidebar" id="usu_config" data-slide="true" href="#" role="button">
                    <i class="fas fa-user-cog  brand-image img-circle elevation-3" style="background-color: #f0f0f5;"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " title="Sair" href="sair.php" role="button">
                    <i class="fas fa-sign-out-alt brand-image img-circle elevation-3" style="background-color: #f0f0f5;"></i>
                </a>
            </li>
        </ul>
    </nav>
<script>
    $(document).ready( function(){ 
      $("#help").click( function(){
        $('#modal_help').modal("show")
      })
/*  */
var myVar = setInterval(myTimer ,1000);
    function myTimer() {
        var d = new Date(), displayDate;
       if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
          displayDate = d.toLocaleTimeString('pt-BR');
       } else {
          displayDate = d.toLocaleTimeString('pt-BR', {timeZone: 'America/Belem'});
       }
          document.getElementById("relogio").innerHTML = displayDate+"h";
    }
    })
</script>
