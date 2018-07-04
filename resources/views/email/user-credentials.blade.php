<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h1>Welcom to Buzzooka CRM, {{ $user->first_name }}</h1>

	Your username is "{{ $user->username }}", <br>
	Your temporary password is "{{ $password }}"
</body>
</html>