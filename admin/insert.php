<?php

require "database.php";

function checkInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = "";

if(!empty($_POST)) {
	$name 				= checkInput($_POST['name']);
	$description 		= checkInput($_POST['description']);
	$price 				= checkInput($_POST['price']);
	$category 			= checkInput($_POST['category']);
	$image 				= checkInput($_FILES['image']['name']);
	$imagePath 			= '../images/'.basename($image);
	$imageExtension 	= pathinfo($imagePath, PATHINFO_EXTENSION);
	$isSucces 			= true;
	$isUploadSuccess 	= false;

	if(empty($name)) {
		$nameError = 'Ce champ ne peut pas etre vide';
		$isSucces = false;
	}
	if(empty($description)) {
		$descriptionError = 'Ce champ ne peut pas etre vide';
		$isSucces = false;
	}
	if(empty($price)) {
		$priceError = 'Ce champ ne peut pas etre vide';
		$isSucces = false;
	}
	if(empty($category)) {
		$categoryError = 'Ce champ ne peut pas etre vide';
		$isSucces = false;
	}
	if(empty($image)) {
		$imageError = 'Ce champ ne peut pas etre vide';
		$isSucces = false;
	} else {
		$isUploadSuccess = true;

		if($imageExtension != 'jpg' && $imageExtension != 'png' && $imageExtension != 'jpeg' && $imageExtension != 'gif'){
			$imageError = 'Les fichiers autorisés sont : .jpg .jpeg .png .gif';
			$isUploadSuccess = false;
		}

		if(file_exists($imagePath)){
			$imageError = "Le fichier n'existe pas";
			$isUploadSuccess = false;
		}

		if($_FILES['image']['size'] > 500000) {
			$imageError = 'Le fichier ne doit pas depasser les 500KB';
			$isUploadSuccess = false;
		}

		if($isUploadSuccess) {
			if(!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)){
				$imageError = 'Il y  a eu une erreur lors du chargement';
				$isUploadSuccess = false;
			}
		}
	}

	if($isSucces && $isUploadSuccess) {
		$db = DataBase::connect();
		$statement = $db->prepare('INSERT INTO items (name, description, price, category, image) values (?,?,?,?,?)');
		$statement->execute(array($name , $description , $price , $category , $image));
		DataBase::disconnect();
		header('location: index.php');
	}
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
			<h1><strong>Ajouter un item  </strong></h1>
			<br>
			<form class="form" role="form" action="insert.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="name">Nom: </label>
					<input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="<?php echo $name;?>">
					<span class="help-inline"><?php echo $nameError; ?></span>
				</div>
				<div class="form-group">
					<label for="description">Description: </label>
					<input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description;?>">
					<span class="help-inline"><?php echo $descriptionError; ?></span>
				</div>
				<div class="form-group">
					<label for="price">Prix: </label>
					<input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Prix" value="<?php echo $price;?>">
					<span class="help-inline"><?php echo $priceError; ?></span>
				</div>
				<div class="form-group">
					<label for="category">Categorie: </label>
					<select class="form-control" id="category" name="category">
						<?php

							$db = DataBase::connect();
							forEach($db->query('SELECT * FROM categories') as $row) {
								echo '<option value='.$row['id'].'>'.$row['name'].'</option>';

							}

							DataBase::disconnect();

						?>
					</select>
					<span class="help-inline"><?php echo $categoryError; ?></span>
				</div>
				<div class="form-group">
					<label form="image">Selectionner une image :</label>
					<input type="file" id="image" name="image">
					<span class="help-inline"><?php echo $imageError; ?></span>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Ajouter</button>
					<a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
				</div>
			</form>
		</div>		
	</div>

</body>
</html>