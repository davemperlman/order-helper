<?php

/*
* Class used for organizing and displaying historical data. Makes predictions
* based on historical averages for new orders as well as tracking financial 
* results of previous orders.
*/
class Order_Helper {

	public  $day;
	public  $category;
	private $data;
	public $new;

	
	function __construct($day, $category) {
		$this->day = $day;
		$this->category = $category;
		$this->populate_orders();
		$this->new = $this->sort_orders();
	}

	//Main Query
	private function populate_orders() {
		global $pdo;
		$this->data = $pdo->query("SELECT * FROM Products INNER JOIN Orders ON Products.id = product_id WHERE category_id = $this->category AND DATE_FORMAT(date, '%w') = $this->day")->fetchALL(PDO::FETCH_ASSOC);
	}

	//Filters the results of the main query so each order is grouped under food type
	private function sort_orders() {
		$tmp = array();
		foreach ($this->data as $key) {
			$tmp[$key['name']][] = array($key['date'], $key['quantity'], $key['price']);
		}
		$output = array();
		foreach ($tmp as $type => $labels) {
			$output[] = array(
				'name' => $type,
				'order_info' => $labels,
			);
		}
		return $output;
	}
	
	//Displays the requested data in tabular form when called.
	public function display() {
		foreach ($this->new as $key => $value) {
			echo "<table>";
			echo "<h2>" . $value['name'] . "</h2>";
			echo "<th>Date</th><th>Quantity</th><th>Price</th>";

			$qty = array();
			$price = array();
			foreach ($value['order_info'] as $info => $spec) {
				echo "<tr>";
				echo "<td>" . $spec[0] . "</td>"; // date
				echo "<td>" . $spec[1] . "</td>"; // qty
					$qty[] = $spec[1];
				echo "<td>" . $spec[2] . "</td>"; // price
					$price[] = $spec[2];
				echo "</tr>";
			}
			echo "<tr>";
			echo "<td id='pre-average'>Average:</td>";
			echo "<td id='average'>" . array_sum($qty)/count($qty) . "</td>";
			echo "<td id='average'>" . array_sum($price)/count($price) . "</td>";
			echo "</tr>";
			echo "</table>";
		}
	}

}