<?php
	$title = "Japanese Jigsaws - Cart";
	$checkoutPage = "false";
	include_once('header.inc');
?>

	<div class="clear"> lalala </div>
	<div class="singleDivWidth">

<?php

		if( isset( $_POST['product'] ) )
		{
			$product = $_POST['product'];
			if( !isset( $_POST['plusButton'] ) && !isset( $_POST['minusButton'] ) )
			{
				if( is_numeric( $_POST['quantity'.str_replace( " ", "_", $_POST['product'] )] ) && $_POST['quantity'.str_replace( " ", "_", 
					$_POST['product'] )] >= 0 )
				{	$_SESSION['cart'][ $product ] = (int) $_POST['quantity'.str_replace( " ", "_", $_POST['product'] ) ];	}
			}
			if( isset( $_POST['plusButton'] ) )
			{	$_SESSION['cart'][ $product ] += 1;	}
			if( isset( $_POST['minusButton'] ) )
			{	$_SESSION['cart'][ $product ] -= 1;	}
		}

		if( $_SESSION['loggedIn'] == "true" )
		{  echo 'Cart for '.$_SESSION["customer"][0].' '.$_SESSION["customer"][1].' <br /> <br />';  }
		else
		{  echo 'Cart for Guest <br /> <br />';  }

		$somethingInCart = false;
		$allFiles = scandir('.');
		$productsArray = array();
		foreach( $allFiles as $file )
		{
			if( preg_match( "(^[A-Z]{1}[a-z]+[ ]?[A-Z]?[a-z]+\.php$)", $file, $matches ) == 1 )
			{  $productsArray[ count( $productsArray ) ] = substr( $matches[0], 0, strlen($matches[0])-4 );  }
		}
		foreach( $productsArray as $product )
		{
			if( isset( $_SESSION['cart'][$product] ) )
			{
				if( $_SESSION['cart'][$product] > 0 )
				{  $somethingInCart = true;  }
				else
				{  unset( $_SESSION['cart'][$product] );  }
			}
		}

		if( !$somethingInCart )
		{
			unset( $_SESSION['cart'] );
		}
		else
		{
			unset( $productsArray );
		}

		if( isset( $_SESSION['cart'] ) )
		{
			echo '<table>
					<col width="150">
					<col width="150">
					<col width="150">
					<col width="150">
					
					<tr>
						<th style="text-align:left"> Product </th>
						<th class="cartTable"> Quantity </th>
						<th class="cartTable"> Unit price </th>
						<th class="cartTable"> Sub-total </th>
					</tr>
			';

			$allFiles = scandir('.');
			$productsArray = array();
			$totalPrice = 0;
			foreach( $allFiles as $file )
			{
				if( preg_match( "(^[A-Z]{1}[a-z]+[ ]?[A-Z]?[a-z]+\.php$)", $file, $matches ) == 1 )
				{  $productsArray[ count( $productsArray ) ] = substr( $matches[0], 0, strlen($matches[0])-4 );  }
			}
			foreach( $productsArray as $product )
			{
				if( isset( $_SESSION['cart'][$product] ) )
				{
					include_once( $product.'.php' );
					echo '
					<tr>
						<td> '.$product.' </td>


						<td class="cartTable">
						<form id="changeInCart" method="post" action=" '.$_SERVER['PHP_SELF'].' " onsubmit=""> 
							<input type="hidden" name="product" value="'.$product.'" />

					<!--  http://stackoverflow.com/questions/7231157/how-to-submit-form-on-change-of-dropdown-list  -->

							<input type="submit" id="plusButton" name="plusButton" value="+" />
							<input type="text" id="quantity" name="quantity'.$product.'" value="'.$_SESSION["cart"][$product].'" 
								onchange="this.form.submit()" 
								size="2" />
							<input type="submit" id="minusButton" name="minusButton" value="-" />
						</form>
						</td>

						<td class="cartTable"> '.number_format( $productArray['price'], 2 ).' </td>
						<td class="cartTable"> '.number_format( $_SESSION['cart'][$product]*$productArray['price'], 2 ).' </td>
					</tr>
					';
					$totalPrice += $_SESSION['cart'][$product]*$productArray['price'];
				}
			}
			echo '<tr> <td></td> <td></td> <td class="cartTable"> Total price: </td> <td class="cartTable"> '.number_format( $totalPrice, 2 ).' </td> </tr>
				<tr> <td></td> <td></td> <td></td> 
				<td> <form id="goToCheckout" method="post" action="checkout.php" onsubmit=""> 
					<input type="submit" id="PtC" name="PtC" value="Proceed to Checkout"  />
					</form> 
				</td> 
				</tr>
				</table>';
		}
		else
		{  echo 'You have nothing in your cart!';  }
?>

	</div>

<?php
	include_once('footer.inc');
?>