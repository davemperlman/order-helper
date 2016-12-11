<?php
// Require Class and Config files to instantiate PDO and Order Obj.
require_once 'config.php';
require_once 'class.orderhelper.php';

$categories = $pdo->query("SELECT name FROM Categories")->fetchAll();
$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

// Instantiate a new Order_Analyzer object when arguments are set by end user.
if ( isset($_GET['submit']) ) {
	$helper = new Order_Helper($_GET['day'], $_GET['category']+1);
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="_css/style.css">
		<title>Inventory Analyzer</title>
	</head>
	<body>
	<div id="filler">
		<h1>Inventory Ordering App</h1>
	</div>
		<aside>	
			<h2>Previous Orders</h2>		
			<form action="" method="get">
				<label for="day">Day</label>
				<select name="day" id="day" required>

					<?php foreach ($days as $key => $day): ?>
						<option value="<?php echo $key ?>" <?php echo ($_GET['day'] == $key) ? 'selected' : '';  ?>><?php echo $day; ?></option>
					<?php endforeach ?>

				</select>
				<label for="category">Category</label>
				<select name="category" id="category" required>

					<?php foreach ($categories as $key => $category): ?>
						<option value="<?php echo $key ?>" <?php echo ($_GET['category'] == $key) ? 'selected' : ''; ?>><?php echo "$category[name]"; ?></option>
					<?php endforeach ?>

				</select>
				<button type="submit" name="submit"><span>Submit</span></button>
			</form>
		</aside>
		<section id="info">
			<?php isset($_GET['submit']) ? $helper->display() : null; ?> <!-- Display relevant, parsed data: class.orderhelper.php line 46 -->
		</section>
	</body>
</html>
