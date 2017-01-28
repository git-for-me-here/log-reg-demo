<?php if(!isset($_POST) || !empty($_POST)): exit(); ?>
<?php else: ?>
	<div id="register" class="form">
		<h1> Регистрация <small>Пожалуйста, заполните все поля</small></h1> 
		<p> 
			<label for="username" class="uname" data-icon="Y">Ваше имя</label>
			<input id="username" name="username" class="checkfield" type="text" placeholder="John Doe" />
		</p>
		<p> 
			<label for="phonenumber" class="phone" data-icon="Z">Ваш номер телефона</label>
			<input id="phonenumber" name="phonenumber" class="checkfield" type="tel" placeholder="+79521687895" />
		</p>
		<p> 
			<label for="email" class="youmail" data-icon="#" > Ваш e-mail</label>
			<input id="email" name="email" class="checkfield" type="email" placeholder="name@domain.com"/> 
		</p>
		<p> 
			<label for="password" class="youpasswd" data-icon="2">Ваш пароль </label>
			<input id="password" name="password" class="checkfield"" type="password" placeholder="не менее 8 символов" />
		</p>
		<p> 
			<label for="password_confirm" class="youpasswd" data-icon="2">Подтвердите ваш пароль </label>
			<input id="password_confirm" name="password_confirm" class="checkfield" type="password" placeholder="не менее 8 символов" />
		</p>
		<div class="captcha">
			<div class="g-recaptcha" data-sitekey="<?= PUBLIC_KEY ?>" style="display: inline-block;"></div>
			<div><input type="hidden" name="captcha" value="0" class="checkfield"></div>
		</div>
		<p class="signup button"> 
			<input id="signup" type="button" value="Регистрация"/> 
		</p>
		<p class="links">  
			Уже зарегистрированы ?
			<a href="login"> Войти на сайт </a>
		</p>
	</div>
<?php endif;?>