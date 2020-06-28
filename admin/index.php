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
			<h1><strong>Liste des Items  </strong><a href="insert.php" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-plus"></span>Ajouter</a></h1>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Catégorie</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>

					<?php

						require 'database.php';

						$db = DataBase::connect();
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$statement = $db->query('SELECT items.id, items.name, items.description, items.price, categories.name AS category FROM items LEFT JOIN categories ON items.category = categories.id ORDER BY items.id DESC');


						while($item = $statement->fetch()) {
							echo '<tr>';
							echo '<td>'.$item['name'].'</td>';
							echo '<td>'.$item['description'].'</td>';
							echo '<td>'.number_format((float)$item['price'],2,'.','').'</td>';
							echo '<td>'.$item['category'].'</td>';
							echo '<td width="300">';
							echo '<a href="view.php?id='.$item['id'].'" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span> Voir</a>';
							echo ' ';
							echo '<a href="update.php?id='.$item['id'].'" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Mofidier</a>';
							echo ' ';
							echo '<a href="delete.php?id='.$item['id'].'" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Supprimer</a>';
							echo '</td>';
							echo '</tr>';
						}

						DataBase::disconnect();
						

					?>

				</tbody>
			</table>
		</div>		
	</div>

</body>
</html>