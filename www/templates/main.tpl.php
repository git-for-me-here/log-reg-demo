<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Log-Reg-Demo</title>
		<link rel="stylesheet" type="text/css" href="templates/css/style.css">
	</head>
	<body>
		<div class="container" >
            <div id="form" class="wrapper">
				<!-- Проверяем авторизован ли юзер -->
				<?php if(Helper::UserStatus() == false) : ?>
					<?php if(route::dispatcher() == "login") : ?>
						<?php include_once 'login.tpl.php'; ?>
					<?php elseif(route::dispatcher() == "signup") : ?>
						<?php include_once 'sign.tpl.php'; ?>
					<?php elseif(route::dispatcher() == "recover") : ?>
						<?php include_once 'repassword.tpl.php'; ?>
					<?php else : ?>	
						<?php if (isset($_SESSION['info']) && !empty($_SESSION['info'])) :?>
							<?php if ($_SESSION['info'] == "success") :?>
								<div id="main">
									<div class="success"></div>
									<h2>Аккаунт успешно активирован!</h2>
								</div>
							<?php endif; ?>
							<?php if ($_SESSION['info'] == "error") :?>
								<div id="main">
									<div class="err"></div>
									<h2>
										Произошла ошибка при активации акаунта! <br>
										Пожалуйста, обратитесь к Администрации сайта.
									</h2>
								</div>
							<?php endif; ?>
							<?php unset($_SESSION['info']); ?>
						<?php else : ?>
							<div id="main">
								<div class="lock"></div>
								<h2>
									Уважаемый посетитель, Вы зашли на сайт как незарегистрированный пользователь. <br>
									Мы рекомендуем Вам зарегистрироваться либо войти на сайт под своим логином.
								</h2>
								<p class="liks-on-main">
									<a href="signup">Зарегистрироваться</a>
									<a href="login"> Войти на сайт </a>
								</p>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				<?php else : ?>	
					<div id="main">
						<div class="unlock"></div>
						<h2>
							Рады приветсвовать, <?= $_SESSION['username'] ?>! <br>
							Если Вы видете это сообщение, значит Вы авторизованы на этом сайте.
						</h2>
						<p class="liks-on-main">
							<a href="logout">Выйти</a>
						</p>
					</div>
				<?php endif; ?>
			</div>
        </div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> <!-- jQuery 3.1.1 -->
		<script type="text/javascript" src="templates/js/ajax.js"></script>
		<script type="text/javascript">
			$(window).on('load', function() {
				$('#form').validate();				
			});
		</script>
		
		<script src='https://www.google.com/recaptcha/api.js'></script>	<!-- reCAPTCHA -->
	</body>
</html>