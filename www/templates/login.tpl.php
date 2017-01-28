<?php if(!isset($_POST) || !empty($_POST)): exit(); ?>
<?php else: ?>
	<div id="login" class="form">
		<h1>Вход на сайт</h1> 
		<p> 
			<label for="username" class="uname" data-icon="Y" > Логин</label>
			<input id="username" name="login" class="checkfield" type="email" placeholder="Ваш e-mail"/>
		</p>
		<p> 
			<label for="password" class="youpasswd" data-icon="2"> Пароль </label>
			<input id="password" name="pswd" class="checkfield" type="password" placeholder="Ваш пароль" /> 
		</p>
		<p class="keeplogin"> 
			<input type="checkbox" class="css-checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" /> 
			<label for="loginkeeping" class="css-label lite-cyan-check">Запомнить меня</label>
		</p>
		<p class="login button"> 
			<input id="auth" type="button" value="Войти" /> 
		</p>
		<p class="links">
			Нет учетной записи?
			<a href="signup">Зарегистрироваться</a>
		</p>
		<p class="links">
			Забыли пароль?
			<a href="recover">Восстановить</a>
		</p>
	</div>
<?php endif;?>