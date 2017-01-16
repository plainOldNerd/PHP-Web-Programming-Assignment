<?php
	session_start();
	if( !(isset($_SESSION['loggedIn'])) )
	{	$_SESSION['loggedIn'] = "false";	}

	$users = file("customers.txt");

	if( isset($_POST['Login']) )
	{
		$_POST['email'] = trim($_POST['email']);
		$_POST['password'] = trim($_POST['password']);
		if( !empty($_POST['email']) && !empty($_POST['password']) )
		{
			foreach( $users as $user )
			{
				$user = explode("\t",$user);

				if( $_POST['email'] == $user[2] && md5( $_POST['password'] ) == $user[4] )
				{
					$_SESSION['customer'] = $user;
					$_SESSION['loggedIn'] = "true";
				}
			}
		}
	}
	if( isset($_POST['Logout']) )
	{
		unset( $_SESSION['customer'] );
		unset( $_SESSION['cart'] );
		$_SESSION['loggedIn'] = "false";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">


<html xmlns="http://www.w3.org/1999/xhtml" lang="ja">

<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8">
    
	<title> 
	<?php    echo $title    ?>
	</title>
	<link rel="stylesheet" type="text/css" href="styles.css"></link>
	<script src="functions.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		$(document).ready(function()
		{	$("#headerAndDesc").click(function()
			{	$("#descOnly").toggle();
			});
		});
	</script>
  
</head>

<body>

	<div>

      
		<div id="masthead">
                    <img id="logo" src="images/logo.png" alt="logo goes here" />

      
		</div>

		<div id="navi">
                <!--  We do this as a list and NOT a table for ease of adding more tabs later.  -->
			<ul id="naviUL">

				<li>  <a href="index.php" 
					<?php
						if( substr( $title, 19 ) == "Home" )
						{	echo ' style="background-color:blue"';	}
					?>
					>  Home </a>  
				</li>

                                <li>  <a href="products.php"
					<?php
						if( substr( $title, 19 ) == "Products" || isset( $_GET['product'] ) )
						{	echo ' style="background-color:blue"';	}
					?>
					>  Products  </a>

                                   <ul>

		<?php
			$allFiles = scandir('.');
			foreach( $allFiles as $file )
			{
				if( preg_match( "(^[A-Z]{1}[a-z]+[ ]?[A-Z]?[a-z]+\.php$)", $file, $matches ) == 1)
				{
					$matches = $matches[0];
					$matches = substr( $matches, 0, strlen($matches)-4 );
					echo '<li> <a href="products.php?product='.$matches.'"';
					if( $matches == $_GET['product'] ){	echo ' style="background-color:blue"';	}
					echo '> '.$matches.' </a>  
						</li>';
				}

			}
		?>

                                   </ul>

                                </li>

				<li>  <a href="cart.php"
					<?php
						if( substr( $title, 19 ) == "Cart" )
						{	echo ' style="background-color:blue"';	}
					?>
					> Cart  </a>  
				</li>

                                <li>  <a href="site map.php" style="word-spacing:1px
					<?php
						if( substr( $title, 19 ) == "Site Map" )
						{	echo '; background-color:blue';	}
					?>
					"> Site Map </a>  
				</li>
        
			</ul>
		</div>

		<?php
		if( $_SESSION['loggedIn'] == "false" )
		{	
		?>
	                <div id="logIn">
	                    	<form id="logInForm" method="post" action=" <?php $_SERVER['PHP_SELF']?> " onsubmit="">
	                        <table>
					<tr>
						<th>  Email:  </th>
						<th>  Password:  </th>
                	                <td>  <input type="submit" id="submit" value="Login" name="Login"/>  </td>
                        	    	</tr>
								
					<tr>
	                                	<td>  <input type="text" id="email" name="email" value="alice@wonderland.com" size="20" />  </td>
	                                	<td>  <input type="password" id="password" name="password" value="whi73r4bbit" size="20" />  </td>
					</tr>
				</table>
				<input type="hidden" id="itemQuantity" name="itemQuantity" value="1" />
				<input type="hidden" id="checkoutFirstName" name="checkoutFirstName" />
				<input type="hidden" id="checkoutLastName" name="checkoutLastName" />
				<textarea id="checkoutAddress" name="checkoutAddress" style="visibility:hidden"></textarea>
				<input type="hidden" id="checkoutEmail" name="checkoutEmail" />
				<input type="hidden" id="checkoutPhoneNo" name="checkoutPhoneNo" />
				<input type="hidden" id="checkoutRadio" name="checkoutRadio" />
				<input type="hidden" id="signMeUp" name="signMeUp" value="" />

				</form>
                	</div>
		<?php
		} 
		else
		{
		?>
			<div id="logIn"> 
				<form id="logOutForm" method="post" action=" 
					<?php if( $checkoutPage == "true" )
					{	echo 'cart.php';	}
					?> " onsubmit="">
					Hello <?php echo $_SESSION['customer'][0]." ".$_SESSION['customer'][1]; ?> <br />
					<input type="submit" id="Logout" value="Logout (Clear Cart)" name="Logout" onclick="" /> <br />
					<input type="hidden" id="itemQuantity" name="itemQuantity" value="1" />
					<input type="hidden" id="checkoutFirstName" name="checkoutFirstName" />
					<input type="hidden" id="checkoutLastName" name="checkoutLastName" />
					<textarea id="checkoutAddress" name="checkoutAddress" style="visibility:hidden"></textarea>
					<input type="hidden" id="checkoutEmail" name="checkoutEmail" />
					<input type="hidden" id="checkoutPhoneNo" name="checkoutPhoneNo" />
					<input type="hidden" id="checkoutRadio" name="checkoutRadio" />
					<input type="hidden" id="signMeUp" name="signMeUp" value="" />
				</form>
			</div>
		<?php
		}
		?>

	</div>

	<div id="contents">