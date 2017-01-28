<?php if(!isset($_POST) || !empty($_POST)): exit(); ?>
<?php else: ?>
	<div id="login" class="form">
		<h1>Восстановление<br>пароля</h1> 
		<p> 
			<label for="username" class="uname" data-icon="#" > Введите email, указанный при регистрации</label>
			<input id="username" name="email" class="checkfield" type="email" placeholder="Ваш e-mail"/>
		</p>
		<p class="login button"> 
			<input id="recover" type="button" value="Восстановить" /> 
		</p>
	</div>
<?php endif;?>