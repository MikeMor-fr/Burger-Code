<?php

require "database.php";

function checkInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if(!empty($_GET['id'])) {
	$id = checkInput($_GET['id']);
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
		$isImageUpdated = false;

	} else {

		$isImageUpdated = true;
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

	if(($isSucces && $isImageUpdated && $isUploadSuccess) || ($isSucces && !$isImageUpdated)) {

		$db = DataBase::connect();

		if($isImageUpdated) {

			$statement = $db->prepare('UPDATE items set name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?');
			$statement->execute(array($name , $description , $price , $category , $image, $id));

		} else {

			$statement = $db->prepare('UPDATE items set name = ?, description = ?, price = ?, category = ? WHERE id = ?');
			$statement->execute(array($name , $description , $price , $category , $id));
		}

		DataBase::disconnect();
		header('location: index.php');
	} else if ($isImageUpdated && !$isUploadSuccess) {
		
		$db = DataBase::connect();
		$statement = $db->prepare("SELECT image FROM items WHERE id = ?");
		$statement->execute(array($id));
		$item = $statement->fetch();
		$image = $item['image'];
		DataBase::disconnect();
	}

} else {

	$db = DataBase::connect();

	$statement = $db->prepare('SELECT * FROM items WHERE id = ?');
	$statement->execute(array($id));
	$item = $statement->fetch();
	$name 			= $item['name'];
	$description 	= $item['description'];
	$price 			= $item['price'];
	$category 		= $item['category'];
	$image 			= $item['image'];

	DataBase::disconnect();

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
			<div class="col-sm-6">
				<h1><strong>Modifier un item  </strong></h1>
				<br>
				<form class="form" role="form" action="<?php echo 'update.php?id='.$id; ?>" method="post" enctype="multipart/form-data">
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
									if($row['id'] == $category) {
										echo '<option selected="selected" value='.$row['id'].'>'.$row['name'].'</option>';
									} else {
										echo '<option value='.$row['id'].'>'.$row['name'].'</option>';
									}
								}

								DataBase::disconnect();

							?>
						</select>
						<span class="help-inline"><?php echo $categoryError; ?></span>
					</div>
					<div class="form-group">
						<label>Image :</label>
						<p><?php echo $image; ?></p>
						<label form="image">Selectionner une image :</label>
						<input type="file" id="image" name="image">
						<span class="help-inline"><?php echo $imageError; ?></span>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
						<a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
					</div>
				</form>
			</div>
			<div class="col-sm-6 site">
				<div class="thumbnail">
					<img src="<?php echo '../images/'.$image;?>" alt="<?php echo $name;?>">
					<div class="price"><?php echo number_format((float)$price,2,'.','');?> €</div>
					<div class="caption">
						<h4><?php echo $name;?></h4>
						<p><?php echo $description;?></p>
						<a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart"></span> Commander</a>
					</div>
				</div>
			</div>
		</div>		
	</div>
</body>
</html>