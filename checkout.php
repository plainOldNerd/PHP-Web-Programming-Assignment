<?php
	$title = "Japanese Jigsaws - Give us your money!";
	$checkoutPage = "true";
	include_once('header.inc');
?>

	<div class="clear"> lalala </div>

	<div class="singleDivWidth">

	<!--  PHP check and display checkout page or order confirmation depending...  -->

	<?php
		$dispOrderConf = "true";

		if( isset( $_POST[ 'PtC' ] ) || isset( $_POST[ 'Login' ] ) || isset( $_POST[ 'Logout' ] ) || !isset( $_SESSION[ 'cart' ] ))
		{
			$dispOrderConf = "false";
		}

		if( 	$dispOrderConf == "true" )
		{	if( isset( $_POST[ 'checkout' ] ) )
			{
				if( $_POST[ 'firstName' ] == "" )
				{
					$errorFirstName = "Please enter your first name.";
					$dispOrderConf = "false";
				}
				if( $_POST[ 'lastName' ] == "" )
				{
					$errorLastName = "Please enter your last name.";
					$dispOrderConf = "false";
				}
				if( $_POST[ 'address' ] == "" )
				{
					$errorAddress = "Please enter your address.";
					$dispOrderConf = "false";
				}
	
				//  Note that this does NOT match single quotes, etc, to enforce email address rules as written in Wikipedia.
				//  It simply doesn't allow characters not allowed by FILTER_SANITIZE_EMAIL
	
				if( !( preg_match( "(^[a-zA-Z0-9\$\-_\.\+!*\'\{\}\|\^~\[\]`#%/\?@&=]+@[a-z\.]+\.[a-z\.]+$)", $_POST[ 'formEmail' ] ) == 1 ) )
				{
					$errorEmailAddress = "Please enter a valid email address.";
					$dispOrderConf = "false";
				}
				if( !( preg_match( "(^(\+|\([0-9]{2}\)|[0-9]{1})[0-9 ]+$)", $_POST[ 'phoneNo' ] ) == 1 ) )
				{
					$errorPhone = "Please enter a valid phone number.";
					$dispOrderConf = "false";
				}
				else
				{
					$i = 0;
					for( $i = 0; $i < count( $_POST[ 'phoneNo' ] )-1; $i++ )
					{
						if( ( $_POST[ 'phoneNo' ][$i] == " " ) && ( $_POST[ 'phoneNo' ][$i+1] == " " ) )
						{
							$errorPhone = "Please enter only single spaces.";
							$dispOrderConf = "false";
							break;
						}
					}
				}
	
				//  Validate credit card number format and number by Luhn algorithm
	
				$creditCard = $_POST[ 'creditCard' ];
				if( !( preg_match( "(^[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]?[\- ]?[0-9]?[\- ]?[0-9]?[\- ]?[0-9]?[\- ]?[0-9]?[\- ]?[0-9]{1}$)", $creditCard ) == 1 ) )
				{
					$errorCC = "Please enter a valid credit card number.";
					$dispOrderConf = "false";
					$i = -3;
				}
				else
				{
					$i = 11;
					for( $i = 11; $i < count( $creditCard )-2; $i++ )
					{
						if( ( $creditCard[$i] == " " || $creditCard[$i] == "-" ) && ( $creditCard[$i+1] == " " || $creditCard[$i+1] == "-" ) )
						{
							$errorCC = "Please enter only single spaces or hyphens.";
							$dispOrderConf = "false";
							break;
						}
					}
				}
				if( !isset( $errorCC ) )
				{
					$creditCard = str_replace( "-", "", $creditCard );	$creditCard = str_replace( " ", "", $creditCard );
					$creditCard = array_map('intval', str_split( $creditCard ) );
					$newCreditArray = array();
	
					for( $j = count( $creditCard )-1; $j >= 0; $j-- )
					{
						if( ( count( $creditCard ) - $j ) % 2 == 0 )
						{
							$singleDigit = $creditCard[$j];
							if( $singleDigit*2 >= 10 )
							{
								$doubleDigit = str_split( $singleDigit*2 );
								$singleDigit = intval( $doubleDigit[0] ) + intval( $doubleDigit[1] );
								$newCreditArray[ count( $newCreditArray ) ] = $singleDigit;
							}
							else
							{
								$newCreditArray[ count( $newCreditArray ) ] = 2*$creditCard[$j];
							}
						}
						else
						{
							$newCreditArray[ count( $newCreditArray ) ] = $creditCard[$j];
						}
					}
					$total = 0;
					for( $j = 0; $j < count( $newCreditArray ); $j++ )
					{
						$total += $newCreditArray[$j];
					}
					if( !( $total % 10 == 0 ) )
					{	
						$errorCC = "Please enter a valid credit card number.";
						$dispOrderConf = "false";
					}
				}
				date_default_timezone_set( 'Australia/Melbourne' );
				$thisMonth = date( 'n' );		$thisYear = date( 'Y' );
				$expiryMonth = $_POST[ 'month' ];	$expiryYear = $_POST[ 'year' ];
				if( $expiryYear == $thisYear && $expiryMonth < $thisMonth )
				{
						$errorExpiry = "Your card has expired.";
						$dispOrderConf = "false";
				}
			}
		}

		if( $dispOrderConf == "true" )
		{
		//  Display order confirmation page

			echo '
			<table>
				<tr>
					<td> Name: </td>
					<td> '.$_POST[ 'firstName' ].' '.$_POST[ 'lastName' ].' </td>
				</tr>
				<tr>
					<td> Address: </td>
					<td> '.$_POST[ 'address' ].' </td>
				</tr>
				<tr>
					<td> Email address: </td>
					<td> '.$_POST[ 'formEmail' ].' </td>
				</tr>
				<tr>
					<td> Phone number: </td>
					<td> '.$_POST[ 'phoneNo' ].' </td>
				</tr>
				<tr>
					<td> Delivery by: </td>
					<td> '.$_POST[ 'radioButton' ].' </td>
				</tr>
			</table>
			Ordered:
			';
			$allFiles = scandir('.');
			$productsArray = array();
			$totalPrice = 0;
			echo '
			<table>
			';
			foreach( $allFiles as $file )
			{
				if( preg_match( "(^[A-Z]{1}[a-z]+[ ]?[A-Z]?[a-z]+\.php$)", $file, $matches ) == 1 )
				{
					$productsArray[ count( $productsArray ) ] = substr( $matches[0], 0, strlen($matches[0])-4 );
				}
			}
			$total = 0;
			foreach( $productsArray as $product )
			{
				if( isset( $_SESSION['cart'][$product] ) )
				{
					include( $product.'.php' );
					echo '
						<tr>
							<td> '.$_SESSION[ 'cart' ][$product].' x </td>
							<td> '.$product.' </td>
							<td> @ $'.number_format( $productArray['price'], 2 ).' each </td>
							<td style="text-align:right"> Sub-total: $'.number_format( $_SESSION['cart'][$product]*$productArray['price'], 2 ).' </td>
						</tr>
					';
					$total += $_SESSION['cart'][$product]*$productArray['price'];
				}
			}
			echo '
						<tr>
							<td colspan="4" style="text-align:right"> Total: $'.number_format( $total, 2 ).'
			</table>
			on '.date( 'H:i d/m/Y' )
			;

			$orderDetails = "";
			$orderDetails = $orderDetails."Name: \t\t".$_POST[ 'firstName' ]." ".$_POST[ 'lastName' ]."\n";
			$orderDetails = $orderDetails."Address: \t".$_POST[ 'address' ]."\n";
			$orderDetails = $orderDetails."Email address: \t".$_POST[ 'formEmail' ]."\n";
			$orderDetails = $orderDetails."Phone number: \t".$_POST[ 'phoneNo' ]."\n";
			$orderDetails = $orderDetails."Delivery by: \t".$_POST[ 'radioButton' ]."\nOrdered: \n";

			$allFiles = scandir('.');
			$productsArray = array();
			$total = 0;
			foreach( $allFiles as $file )
			{
				if( preg_match( "(^[A-Z]{1}[a-z]+[ ]?[A-Z]?[a-z]+\.php$)", $file, $matches ) == 1 )
				{
					$productsArray[ count( $productsArray ) ] = substr( $matches[0], 0, strlen($matches[0])-4 );
				}
			}
			foreach( $productsArray as $product )
			{
				if( isset( $_SESSION['cart'][$product] ) )
				{
					include( $product.'.php' );
					$orderDetails = $orderDetails."   ".$_SESSION[ 'cart' ][$product]." x \t";
					$orderDetails = $orderDetails.$product;
					if( strlen( $product ) < 8)
					{	$orderDetails = $orderDetails."\t";	}
					$orderDetails = $orderDetails."\t @ ";
					$orderDetails = $orderDetails.number_format( $productArray['price'], 2 )." each \t";
					$orderDetails = $orderDetails."Sub-total: ";
					if( strlen( number_format( $_SESSION['cart'][$product]*$productArray['price'], 2 ) ) < 6 )
					{	$orderDetails = $orderDetails." ";	}
					$orderDetails = $orderDetails.number_format( $_SESSION['cart'][$product]*$productArray['price'], 2 )." \n";
					$total += number_format( $_SESSION['cart'][$product]*$productArray['price'], 2 );
				}
			}
			$orderDetails = $orderDetails."\n\t\t\t\t\tTotal price: ".number_format( $total, 2 )."\n";
			$orderDetails = $orderDetails."on ".date( 'H:i d/m/Y' )."\n\n";

			$fp = fopen( "orders.txt", "a" );
			fwrite( $fp, $orderDetails );
			unset( $_SESSION[ 'cart' ] );
		}

		else
		{
		//  Display the same checkout page with error messages, or re-direct to Cart page if 'cart' is not set

		if( !isset( $_SESSION[ 'cart' ] ) )
		{
			header( "location: cart.php" );
		}

		echo '<form id="checkoutForm" method="post" action=" '.$_SERVER['PHP_SELF'].' " onsubmit="">
		';

		if( isset( $_POST[ 'radioButton' ] ) )
		{
			echo '
				<script>
				{	document.getElementById( "checkoutRadio" ).value = '.$_POST[ "radioButton" ].';	}
				</script>
			';
		}
		?>
			<table>
				<tr>
					<td>  First name:  </td>
					<td>  <input type="text" id="firstName" name="firstName" 
		<?php
						if( $_SESSION['loggedIn'] == "true" )
						{
							if( !isset( $_POST['firstName'] ) && !isset( $_POST['checkoutFirstName'] ) 
								|| isset( $_POST['firstName'] ) && $_POST['firstName'] == "" 
								|| isset( $_POST['checkoutFirstName'] ) && $_POST['checkoutFirstName'] == "" )
							{	echo 'value="'.$_SESSION['customer'][0].'" ';	}
							if( isset( $_POST['firstName'] ) )
							{	echo 'value="'.$_POST["firstName"].'" ';	}
							if( isset( $_POST['checkoutFirstName'] ) )
							{	echo 'value="'.$_POST["checkoutFirstName"].'" ';	}
						}
						else
						{
							if( isset( $_POST['firstName'] ) )
							{	echo 'value="'.$_POST["firstName"].'" ';	}
						}
		?>
						required="required" style="width:100%"
						onchange="{
							document.getElementById( 'checkoutFirstName' ).value = document.getElementById( 'firstName' ).value;
							}"
						/>
		<?php

						if( isset( $_POST[ "firstName" ] ) && $_POST[ "firstName" ] != "" )
						{
							echo '<script>
							{	
								document.getElementById("checkoutFirstName").value = 
								document.getElementById( "firstName" ).value;
							}
							</script>
							';
						}
						if( isset( $errorFirstName ) )
						{
							echo '<br /> <span style="color:red; font-size:67%"> '.$errorFirstName.' </span>';
						}
		?>
					</td>
				</tr>
				<tr>
					<td>  Last name: </td>
					<td>  <input type="text" id="lastName" name="lastName" 
		<?php

						if( $_SESSION['loggedIn'] == "true" )
						{
							if( !isset( $_POST['lastName'] ) && !isset( $_POST['checkoutLastName'] ) 
								|| isset( $_POST['lastName'] ) && $_POST['lastName'] == "" 
								|| isset( $_POST['checkoutLastName'] ) && $_POST['checkoutLastName'] == "" )
							{	echo 'value="'.$_SESSION['customer'][1].'" ';	}
							if( isset( $_POST['lastName'] ) )
							{	echo 'value="'.$_POST["lastName"].'" ';	}
							if( isset( $_POST['checkoutLastName'] ) )
							{	echo 'value="'.$_POST["checkoutLastName"].'" ';	}
						}
						else
						{
							if( isset( $_POST['lastName'] ) )
							{	echo 'value="'.$_POST["lastName"].'" ';	}
						}
		?>
						required="required" style="width:100%"
						onchange="{
							document.getElementById( 'checkoutLastName' ).value = document.getElementById( 'lastName' ).value;
							}"
						/>
		<?php
						if( isset( $_POST[ 'lastName' ] ) && $_POST[ 'lastName' ] != "" )
						{
							echo '<script>
							{	
								document.getElementById("checkoutLastName").value = 
								document.getElementById( "lastName" ).value;
							}
							</script>
							';
						}
						if( isset( $errorLastName ) )
						{
							echo '<br /> <span style="color:red; font-size:67%"> '.$errorLastName.' </span>';
						}
		?>
					</td>
				</tr>
				<tr>
					<td>  Address:  </td>
					<td>  <textarea rows="4" cols="30" id="address" name="address" required="required"
						onchange="{
							document.getElementById( 'checkoutAddress' ).value = document.getElementById( 'address' ).value;
							}"><?php
						if( isset( $_POST['address'] ) )
						{  echo trim( $_POST['address'] );  }
						if( isset( $_POST['checkoutAddress'] ) )
						{  echo trim( $_POST['checkoutAddress'] );  }
							?></textarea>
		<?php
						if( isset( $_POST[ 'address' ] ) && $_POST[ 'address' ] != "" )
						{
							echo '<script>
							{	
								document.getElementById("checkoutAddress").value = 
								document.getElementById( "address" ).value;
							}
							</script>
							';
						}
						if( isset( $errorAddress ) )
						{
							echo '<br /> <span style="color:red; font-size:67%"> '.$errorAddress.' </span>';
						}
		?>
					</td>
				</tr>
				<tr>
					<td>  Email:  </td>
					<td>  <input type="email" id="formEmail" name="formEmail"
		<?php
						if( $_SESSION['loggedIn'] == "true" )
						{
							if( !isset( $_POST['formEmail'] ) && !isset( $_POST['checkoutEmail'] ) 
								|| isset( $_POST['formEmail'] ) && $_POST['formEmail'] == "" 
								|| isset( $_POST['checkoutEmail'] ) && $_POST['checkoutEmail'] == "" )
							{	echo 'value="'.$_SESSION['customer'][2].'" ';	}
							if( isset( $_POST['formEmail'] ) )
							{	echo 'value="'.$_POST["formEmail"].'" ';	}
							if( isset( $_POST['checkoutEmail'] ) )
							{	echo 'value="'.$_POST["checkoutEmail"].'" ';	}
						}
						else
						{
							if( isset( $_POST['formEmail'] ) )
							{	echo 'value="'.$_POST["formEmail"].'" ';	}
						}
		?>
						style="width:100%"
						onchange="{
							document.getElementById( 'checkoutEmail' ).value = document.getElementById( 'formEmail' ).value;
							}"
						/>
		<?php
						if( isset( $_POST[ 'formEmail' ] ) && $_POST[ 'formEmail' ] != "" )
						{
							echo '<script>
							{	
								document.getElementById("checkoutEmail").value = 
								document.getElementById( "formEmail" ).value;
							}
							</script>
							';
						}
						if( isset( $errorEmailAddress ) )
						{
							echo '<br /> <span style="color:red; font-size:67%"> '.$errorEmailAddress.' </span>';
						}
		?>
				</tr>
				<tr>
					<td>  Phone Number:  </td>
					<td>  <input type="tel" id="phoneNo" name="phoneNo"		
		<?php
						if( $_SESSION['loggedIn'] == "true" )
						{
							if( !isset( $_POST['phoneNo'] ) && !isset( $_POST['checkoutPhoneNo'] ) 
								|| isset( $_POST['phoneNo'] ) && $_POST['phoneNo'] == "" 
								|| isset( $_POST['checkoutPhoneNo'] ) && $_POST['checkoutPhoneNo'] == "" )
							{	echo 'value="'.$_SESSION['customer'][5].'" ';	}
							if( isset( $_POST['phoneNo'] ) )
							{	echo 'value="'.$_POST["phoneNo"].'" ';	}
							if( isset( $_POST['checkoutPhoneNo'] ) )
							{	echo 'value="'.$_POST["checkoutPhoneNo"].'" ';	}
						}
						else
						{
							if( isset( $_POST['phoneNo'] ) )
							{	echo 'value="'.$_POST["phoneNo"].'" ';	}
						}
		?>
						style="width:100%"
						onchange="{
							document.getElementById( 'checkoutPhoneNo' ).value = document.getElementById( 'phoneNo' ).value;
							checkPhone();
							}"
						/> <br />
						<span id="errorMessage" style="color:red; font-size:67%"> You entered an invalid phone <br /> number. <br /> Please enter a country code or 
							area <br /> code followed by only digits (0-9) <br /> and single spaces. </span>
						<script>
						{
							$("#errorMessage").hide();
						}
						</script>
		<?php
						if( isset( $_POST[ 'phoneNo' ] ) && $_POST[ 'phoneNo' ] != "" )
						{
							echo '<script>
							{	
								document.getElementById("checkoutPhoneNo").value = 
								document.getElementById( "phoneNo" ).value;
							}
							</script>
							';
						}
						if( isset( $errorPhone ) )
						{
							echo '<span style="color:red; font-size:67%"> '.$errorPhone.' </span>';
						}
		?>
					</td>
				</tr>
				<tr>
					<td>  Delivery method:  </td>
					<td>  
						<input type="radio" name="radioButton" value="Regular post" 
		<?php
							if( isset( $_POST[ 'radioButton' ] ) && $_POST[ 'radioButton' ] == "Regular post" || isset( $_POST[ 'checkoutRadio' ] ) && 
								$_POST[ 'checkoutRadio' ] == "Regular post" || !isset( $_POST[ 'radioButton' ] ) )
							{	echo 'checked="checked" ';	}
		?>						 
						onclick="{
							document.getElementById( 'checkoutRadio' ).value = 'Regular post';
							}"
						/>  Regular post <br />
						<input type="radio" name="radioButton" value="Courier" 
		<?php
							if( isset( $_POST[ 'radioButton' ] ) && $_POST[ 'radioButton' ] == "Courier" || isset( $_POST[ 'checkoutRadio' ] ) && 
								$_POST[ 'checkoutRadio' ] == "Courier")
							{	echo 'checked="checked" ';	}
		?>
						onclick="{
							document.getElementById( 'checkoutRadio' ).value = 'Courier';
							}"
						/>  Courier  <br />
						<input type="radio" name="radioButton" value="Express courier" 
		<?php
							if( isset( $_POST[ 'radioButton' ] ) && $_POST[ 'radioButton' ] == "Express courier" || isset( $_POST[ 'checkoutRadio' ] ) && 
								$_POST[ 'checkoutRadio' ] == "Express courier")
							{	echo 'checked="checked" ';	}
		?>
						onclick="{
							document.getElementById( 'checkoutRadio' ).value = 'Express courier';
							}"
						/>  Express courier
					</td>
				</tr>
				<tr>

				<!--  Note the credit card number is not retained for security reasons  -->

					<td>  Credit card number:  </td>
					<td>  <input type="text" id="creditCard" name="creditCard" style="width:100%" 
						onchange="{
								validCCformat();
							}"
							/> <br />
						<span id="invalidCC" style="color:red; font-size:67%"> Invalid number. <br /> First enter a digit (0-9), then only digits, <br /> and single 
							spaces or hyphens, and lastly <br /> a digit.  <br /> There must be between 13 and 18 digits. </span>
						<script>
						{	$("#invalidCC").hide();		}
						</script>
		<?php
						if( isset( $errorCC ) )
						{
							echo '<span style="color:red; font-size:67%"> '.$errorCC.' </span>';
						}
		?>
				</tr>
				<tr>
					<td>  Expiry:  </td>
					<td>  Month:  <select id="month" name="month" 
							onchange="{
									notExpiredYet();
								}"
							>
		<?php
								for( $i = 1; $i <= 12; $i++ )
								{
									echo '<option value="'.$i.'"> ';
									if( $i < 10 )
									{	echo '0';	}
									echo $i.' </option>';
								}
		?>
						      </select>
					      Year:  <select id="year" name="year"
							onchange="{
									notExpiredYet();
								}"
							>
		<?php
							for( $i = date( Y ); $i <= date( Y )+10; $i++ )
							{
								echo '<option value="'.$i.'"> '.$i.' </option>';
							}
		?>
							</select> <br />
						<span id="cardExpired" style="color:red; font-size:67%"> Your card has expired. </span>
						<script>
						{	$("#cardExpired").hide();	}
						</script>
		<?php
						if( isset( $errorExpiry ) )
						{
							echo '<span style="color:red; font-size:67%"> '.$errorExpiry.' </span>';
						}
		?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:right">  
					<input type="checkbox" name="newsletter" 
		<?php
						if( isset( $_POST[ 'newsletter' ] ) || isset( $_POST[ 'signMeUp' ] ) && $_POST[ 'signMeUp' ] == "on" )
						{	echo 'checked="checked" ';	}
		?>
					onclick="{
						if( document.getElementById( 'signMeUp' ).value == 'on' )
						{	document.getElementById( 'signMeUp' ).value = '';	}
						else
						{	document.getElementById( 'signMeUp' ).value = 'on';	}
						}"
					/>  Sign me up for your newsletter  </td>
				</tr>

				<tr>
					<td></td> <td style="text-align:right"> <input type="submit" name="checkout" value="Checkout" /> </td>
				</tr>

			</table>

		</form>
	<?php
	}

	echo '
	</div>
	';

	include_once('footer.inc');
?>