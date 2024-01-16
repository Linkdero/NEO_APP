<?php require 'inc/config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>
<?php
include_once 'inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
	header("Location: principal.php");
} else {
	if (!empty($_GET['error'])) {
		$id = $_REQUEST['error'];
		$message = NULL;
		switch ($id) {
			case 1:
				$message = "Usuario o contraseña ingresada no son correctos.";
				break;
			default:
				$message = NULL;
				break;
		}
	}

	/*exec("wmic /node:$_SERVER[REMOTE_ADDR] COMPUTERSYSTEM Get UserName", $user);
	echo($user[1]);

	$user= shell_exec("echo %username%");
    echo "user : $user";*/

?>
	<!DOCTYPE html>
	<html lang="en" class="no-js">
	<!-- Head -->

	<head>


		<!-- Meta -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<!-- Favicon -->
		<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">

		<!-- Web Fonts -->
		<!--<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">-->

		<!-- Components Vendor Styles -->
		<link rel="stylesheet" href="./assets/vendor/font-awesome/css/all.min.css">

		<!-- Theme Styles -->
		<link rel="stylesheet" href="./assets/css/theme.css">
	</head>
	<!-- End Head -->

	<body>
		<main class="container-fluid w-100" role="main">
			<div class="row">
				<div class="col-lg-3 d-flex flex-column justify-content-center align-items-center mnh-100vh" style="background-color:#192854">
					<span class="u-login-form py-3 mb-auto text-center" >
						<img class="img-fluid animacion_left_to_right" src="./assets/svg/mockups/LOGO GOBIERNO-01.png" width="200" alt="SAAS APP">
					</span>

					<div class=" animacion_left_to_right">
						<div class="">
							<div class="  ">
								<form class="" action="" method="POST" id="login_form">
									<div class="mb-3">
										<h1 class="h2 text-white">¡Bienvenido a SAAS APP</h1>
										<p class="small text-white">Inicia sesión a la Aplicación con tu email y password registrados.</p>
									</div><br>
									<div class="form-group mb-4">
										<label for="email" class="text-white">Tu email</label>
										<span class="form-icon-wrapper">
											<span class="form-icon form-icon--left">
												<i class="fa fa-user form-icon__item"></i>
											</span>
											<span class="form-icon form-icon--right" style="margin-top:8px;margin-right:0rem; width:7rem">
												@saas.gob.gt
											</span>
											<input id="email" class="form-control form-icon-input-left" name="email" type="text" placeholder="tu.correo">
									</div>

									<div class="form-group mb-4">
										<label for="password" class="text-white">Password</label>
										<span class="form-icon-wrapper">
											<span class="form-icon form-icon--left">
												<i class="fa fa-lock form-icon__item"></i>
											</span>
											<input id="password" class="form-control form-icon-input-left" name="password" type="password" placeholder="Tu Password">
									</div>

									<div class="form-group d-flex justify-content-between align-items-center mb-4">
										<a class="link-muted small" href="account-password-recover.html"></a>
									</div>

									<button class="btn btn-info btn-block" type="submit"><i id="loading" class="fa fa-sync fa-spin" style="display:none;"></i> Login</button>
									<span id="error_message"></span>
								</form>
							</div>
						</div>


					</div>
					<div class="u-login-form text-white py-3 mt-auto">
						<small><i class="far fa-question-circle mr-1"></i> Para cualquier duda, por favor comunicarse a la extesión 2522 o 1002</a>.</small>
					</div>
				</div>

				<div class="col-lg-9 d-none d-lg-flex flex-column align-items-center justify-content-center bg-light">
					<img class="img-fluid position-relative u-z-index-3 mx-5 animacion_left_to_right" src="./assets/svg/mockups/LogoGrande2024-bg.png" alt="Image description">
				</div>
			</div>
		</main>
	</body>

	</html>
	<!-- Global Vendor -->
	<script src="./assets/vendor/jquery/dist/jquery.min.js"></script>
	<script src="./assets/vendor/jquery-migrate/jquery-migrate.min.js"></script>
	<script src="./assets/vendor/popper.js/dist/umd/popper.min.js"></script>
	<script src="./assets/vendor/bootstrap/bootstrap.min.js"></script>

	<!-- Plugins -->
	<script src="./assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="./assets/vendor/chart.js/dist/Chart.min.js"></script>
	<script src="<?php echo $one->assets_folder; ?>/js/pages/login.js"></script>
	<!-- Initialization  -->
	<script src="./assets/js/sidebar-nav.js"></script>
	<script src="./assets/js/main.js"></script>
	<script src="./assets/js/dashboard-page-scripts.js"></script>
	</body>

	</html>
	<?php require 'inc/views/template_footer_start.php'; ?>

	<!-- Page JS Plugins -->
	<script src="<?php echo $one->assets_folder; ?>/js/plugins/jquery-validation/jquery.validate.min.js"></script>



	<!-- Page JS Code -->



	<?php require 'inc/views/template_footer_end.php'; ?>

<?php } ?>
