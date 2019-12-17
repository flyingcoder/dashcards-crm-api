<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h1>Welcome to Buzzooka CRM </h1>

	<h5>Hello, {{ $user->first_name }}</h5>
	<p>
		Your username is "{{ $user->username }}", <br>
		You can set your password by following this link <a href="{{$link}}">{{ $link }}</a>
	</p>
</body>
</html>