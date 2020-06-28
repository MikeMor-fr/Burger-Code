<?php

require "database.php";

if(!empty($_GET['id'])) {
	$id = checkInput($_GET['id']);
}

if(!empty($_POST)){
	$id = checkInput($_POST['id']);
	$db = DataBase::connect();
	$statement = $db->prepare("DELETE FROM items WHERE id = ?");
	$statement->execute(array($id));
	DataBase::disconnect();
	header("Location:index.php");
}

function checkInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Burger Code</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Holtwood+One+SC">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

	<h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger Code <span class="glyphicon glyphicon-cutlery"></span></h1>

	<div class="admin container">
		<div class="row">
			<h1><strong>Supprimer un item  </strong></h1>
			<br>
			<form class="form" role="form" action="delete.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<p class="alert alert-warning">Etes vous sur de vouloir supprimer ?</p>
				<div class="form-actions">
					<button type="submit" class="btn btn-warning">Oui</button>
					<a href="index.php" class="btn btn-default">Non</a>
				</div>
			</form>
		</div>		
	</div>

</body>
</html>