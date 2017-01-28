(function( $ ) {
	  $.fn.validate = function() {
			var $this = this;
 			var fields = 	{
						'login':			function check_login() {
												var err = '';
												var s = $this.find('[name=login]').val();
												if(s.length == 0){
													err += error('Это поле не может быть пустым');
												} else {
													var r = /^[\w\.\d-_]+@[\w\.\d-_]+\.\w{2,4}$/i;
													if (!r.test(s)) {
														err += error('Пожалуйста, введите адрес электронной почты');											
													}

												}
												return err;
											},
						'pswd':				function check_name(){
												var err = '';
												var s = $this.find('[name=pswd]').val();
												if(s.length == 0){
													err += error('Это поле не может быть пустым');
												} 
												return err;
											},
						'username': 		function check_name(){
												var err = '';
												var s = $this.find('[name=username]').val();
												if(s.length == 0){
													err += error('Это поле не может быть пустым');
												}
												return err;
											},
						'phonenumber': 		function check_number(){
												var err = '';
												var s = $this.find('[name=phonenumber]').val();
												if(s.length == 0){
													err += error('Это поле не может быть пустым');
												} else {
													var r = /^\+7\d{10}$/;
													if (!r.test(s)) {
														err += error('Пожалуйста, используйте требуемый формат');											
													}
												}
												return err;
											},
						'email': 			function check_email(){
												var err = '';
												var s = $this.find('[name=email]').val();
												if(s.length == 0){
													err += error('Это поле не может быть пустым');
												} else {
													var r = /^[\w\.\d-_]+@[\w\.\d-_]+\.\w{2,4}$/i;
													if (!r.test(s)) {
														err += error('Пожалуйста, введите адрес электронной почты');											
													}

												}
												
												return err;
											},
						'password': 		function check_name(){
												var err = '';
												var s = $this.find('[name=password]').val();
												if(s.length == 0){
													err += error('Это поле не может быть пустым');
												} else if (s.length < 8) {
													err += error('Длина проля не должна быть меньше 8 символов');
												}
												return err;
											},
						'password_confirm': function check_name(){
												var err = '';
												var s = $this.find('[name=password]').val();
												var s1 = $this.find('[name=password_confirm]').val();
												if(s1.length == 0){
													err += error('Это поле не может быть пустым');
												} else if (s !== s1) {
													err += error('Пароли не совпадают');
												}
												return err;
											},
						'captcha':			function check_captcha(){
												var err = '';
												var s = $this.find('[name=captcha]').val();
												if(s == 0){
													err += error('Для регистрации необходимо пройти проверку');
												}
												return err;
											}	
						}; 
			
			$('#auth').bind('click', function(){
				auth();
			});
			
			$('#signup').bind('click', function(){
				signup();
			});	
			
			$('#recover').bind('click', function(){
				recover();
			});
			
			function auth(){
				if (checkAllFields()){				
					ajaxSend('login',
							$this.find('[name=login]').val(),
							$this.find('[name=pswd]').val(),
							$this.find('[name=loginkeeping]').prop('checked')
					);
				}
			}	
			
			function signup(){	
				if (checkAllFields()){			
					ajaxSend('signup',
							$this.find('[name=username]').val(),
							$this.find('[name=phonenumber]').val(),
							$this.find('[name=email]').val(),
							$this.find('[name=password]').val(),
							$this.find('[name=password_confirm]').val(),
							grecaptcha.getResponse()
					);
				}	
			}
			
			function recover(){
				if (checkAllFields()){				
					ajaxSend('recover',
							$this.find('[name=email]').val()
					);
				}
			}

			function ajaxSend(x1, x2, x3, x4, x5, x6, x7) {
				if (arguments.length == 2) {
					var data = {
						act: x1,
						email: x2
					}
					
					function success(res) {
						var cls = (res.indexOf("ошибка") < 0) ? "info" : "err";
						$this.empty().append('<div class="msg"><div class="' + cls +'"></div><h2>' + res + '</h2></div>');	
					}
				}
				if (arguments.length == 4) {
					var data = {
						act: x1,
						login: x2,
						password: x3,
						loginkeeping: x4
					}
					
					function success(res) {
						if (res == "ok") window.location.replace("index.php");
						else 
							$this.empty().append('<div class="msg"><div class="err"></div><h2>' + res +'</h2></div>');						
					}
				}
				if (arguments.length == 7) {
					var data = {
						act: x1,
						username: x2,
						phonenumber: x3,
						email: x4,
						password: x5,
						password_confirm: x6,
						captcha: x7
					}
					
					function success(res) {
						var cls = (res.indexOf("ошибка") < 0) ? "info" : "err";
						$this.empty().append('<div class="msg"><div class="' + cls +'"></div><h2>' + res +'</h2></div>');	
					}
				}
				
				$.ajax({
					url: window.location.pathname,
					type: "POST",
					data: data,
					dataType: "text",
					success: function(res) { success(res) },
					error: function(xhr, ajaxOptions, thrownError) { alert('Ошибка запроса'); }
				});	
			}
			
			this.find('.checkfield').each(function(){
				$(this).bind('keyup', function(){
					checkSingleField($(this).attr('name'));
				});
			});
			
			function checkAllFields(){
				var result = true;
				if ($("div").is("#register")) {
					var captcha = grecaptcha.getResponse();
					if (!captcha.length) {
						$this.find('[name=captcha]').val(0);
					} else {
						$this.find('[name=captcha]').val(1);
					}
				}
				$this.find('.error').each(function(){
					$(this).remove();
				});
				$this.find('.checkfield').each(function(){
					var fname = $(this).attr('name');
					var errors = '';
					errors = checkfield(fname);		
					if (errors != ''){
						$(this).css('border', '1px solid #ff2b2b');
						$(this).parent().append('<div class="error">' + errors.replace(/<.*?>/g, "") + '</div>');
						result = false;
					}
				});
				return result;
			}
			
			function checkSingleField(fname){
				$this.find('[name='+fname+']').css('border', '1px solid rgba(91, 90, 90, 0.7)');
				$this.find('[name='+fname+']').parent().find('.error').remove();
				if ($this.find('[name='+fname+']').val() != ''){
					var errors = '';
					errors = checkfield(fname);		
					if (errors != ''){
						$this.find('[name='+fname+']').css('border', '1px solid #ff2b2b');
						$this.find('[name='+fname+']').parent().append('<div class="error">' + errors.replace(/<.*?>/g, "") + '</div>');
					}
				}
			}

			function error(s){
				var result = s;
				if(s != ''){
					result = '<li>'+result+'</li>';
				}
				return result;
			}
			
			function checkfield(field){
				if(field != ''){
					return fields[field]();		
				}
			} 
	  };
})(jQuery);