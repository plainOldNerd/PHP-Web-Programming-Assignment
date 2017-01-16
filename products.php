<?php

	if( !(isset($_GET['product'])) )
	{  $title = "Japanese Jigsaws - Products";  }
	else
	{  $title = "Japanese Jigsaws - ".$_GET['product'];  }
	$checkoutPage = "false";
	include_once('header.inc');

	function updateCart( $product, $quantity )
	{
		if( !( isset($_SESSION['cart'][$product]) ) ){  $_SESSION['cart'][$product] = 0;  }
		$_SESSION['cart'][$product] = $quantity;
	}

	//  If product has been ordered
	if( isset($_POST['submit']) )
	{
		$product = $_POST['product'];
		$quantity = $_POST['quantity'];
		updateCart( $_POST['product'], $_POST['quantity'] );
		if( $quantity == 0 ){  unset( $_SESSION['cart'][$product] );  }
	}

	//  All-products page

	if( !(isset($_GET['product'])) )
	{
		$allFiles = scandir('.');
		$productsArray = array();
		foreach( $allFiles as $file )
		{
			if( preg_match( "(^[A-Z]{1}[a-z]+[ ]?[A-Z]?[a-z]+\.php$)", $file, $matches ) == 1 )
			{
				$matches = $matches[0];
				$matches = substr( $matches, 0, strlen($matches)-4 );
				$productsArray[ count($productsArray) ] = $matches;
			}
		}

		foreach( $productsArray as $product )
		{
			include_once( $product.".php" );
			echo '
				<div class="left">
				<a href="products.php?product='.$product.'"><img class="leftImg" src="images/'.$product.'.jpg" alt="Really need a picture here" name="'.$product.'" /></a>
				</div>

				<div class="right">
			        <span style="font-size:200%"> '.$product.' </span>
			        <p> '.$productArray['shortDesc'].' </p>
				</div>
				<div class="clear"> lalala </div>
			';
		}
	}
	
	//  Single-product page

	else
	{
		include_once( $_GET['product'].".php" );
?>
		<div class="productLeft">
			<p style="visibility:hidden"> lalala </p>
<?php
			echo '<img class="productLeftImg" src="images/'.$_GET['product'].'.jpg" alt="really need a picture here" />';
?>
		</div>
 
		<div class="productRight">
<?php

		//  http://www.w3schools.com/jquery/eff_toggle.asp

			echo '<span id="headerAndDesc" style="font-size:200%"> '.$productArray['heading'].' </span> <br /> <br />
			<div id="descOnly"> '.$productArray['longDesc'].' </div> 
			';
?>

			<div id="orderBox">
			<form id="qtyOrdered" method="post" action="" onsubmit="">

<?php
			if( isset($_SESSION['cart'][$_GET['product']] ) )
			{
				echo '<span style="font-size:67%"> Quantity currently on order: '.$_SESSION["cart"][$_GET["product"]].'</span> <br />';
			}
?>

			Total quantity to be ordered:
<?php
			echo '<input type="hidden" id="product" name="product" value="'.$_GET['product'].'" /> 
			<br />
			<input type="button" id="plusButton" name="plusButton" value="+" onclick="plusScript( '.$productArray['price'].' );" />
			<input type="text" id="quantity" name="quantity" value="';
			if( isset( $_POST['Login'] ) && isset( $_POST['itemQuantity'] ) )
			{	echo $_POST['itemQuantity'];	}
			else
			{	echo '1';	}
			echo '" onchange="quantityChanged( '.$productArray['price'].' );" size="5" />
			<input type="button" id="minusButton" name="minusButton" value="-" onclick="minusScript( '.$productArray['price'].' );" />
			<br />
			$ <input type="text" id="totalPrice" name="totalPrice" size="8" value="';
			if( isset( $_POST['Login'] ) && isset( $_POST['itemQuantity'] ) )
			{	echo $_POST['itemQuantity']*$productArray['price'];	}
			else
			{	echo $productArray['price'];	}
			echo '" readonly="readonly" /> <br /> 
			';
?>
			<input type="submit" id="submit" name="submit" value="Add to cart"  />
			</form>
			</div>
		</div>

<?php
	}

	include_once('footer.inc');
?>