<h2>Ajouter une image</h2>
<form action="upload_product.php" method="post" enctype="multipart/form-data">
<input type="file" name="image" accept=".png" required>
<button type="submit">Envoyer</button>
<br>
<input type="button" onclick="window.location.href='mes_produits.php'" value="Ignorer">
</form>