<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ceramika</title>
	<link rel="stylesheet" type="text/css" href="assets/bootstrap-3.3.6-dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<div class="navbar navbar-default navbar-fixed-top" id="topnav">
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="index.php" class="navbar-brand">Ceramika</a>
			</div>


		</div>
	</div>
	<p><br><br></p>
	<p><br><br></p>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8" id="err_msg"></div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<div class="panel panel-primary">
					<div class="panel-heading">Change Password</div>
					<div class="panel-body">
				<form method="post" action="send-email.php" onsubmit="return validate(this);">
					<div class="row">
						<div class="col-md-12">
							<label for="email">Enter Email</label>
							<input type="text" id="email" name="email" class="form-control">
							<span class="err_class" id="email_error"></span>
						</div>
					</div>
					<br><br>
					<div class="col-md-12">
						<input type="submit" class="btn btn-primary" value="Submit" name="send" id="sign_btn">
					</div>

					</div>
					</div>
					</form>
					<div class="panel-footer"></div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>






	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
	<script src="assets/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
	<script src="main.js"></script>
	<script type="text/javascript">
		function validate(form)
		{
			console.log('hh');
			$('.err_class').text('');
			var email = $('#email').val();
			var email_patt = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/;
			var flag = true;
			if(email.length == 0)
			{
				$('#email_error').text('Email is Required');
				flag = false;
			}
			if(!email_patt.test(email))
			{
				$('#email_error').text('Email is Invalid');
				flag = false;
			}
			return flag;
		}
	</script>
</body>
<div class="foot"><footer>
<p> Brought To You By <a href="http://www.gecbh.cteguj.in/" target="_blank">GEC Bhavnagar</a></p>
</footer></div>
<style> .foot{text-align: center;}
</style>
</html>
