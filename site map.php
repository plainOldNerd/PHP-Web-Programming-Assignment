<?php
	$title = "Japanese Jigsaws - Site Map";
	$checkoutPage = "false";
	include_once('header.inc');

	echo '<div class="clear"> lalala </div>
		<div class="singleDivWidth">
	';

	$allFiles = scandir('.');
	foreach( $allFiles as $file )
	{
		if( preg_match( "(^[A-Za-z]+[ ]?[A-Za-z]+\.php$)", $file, $matches ) == 1 )
		{
			if( preg_match( "(^[A-Z]{1}[a-z]+[ ]?[A-Z]?[a-z]+\.php$)", $file, $productMatches ) == 1 )
			{
				$productMatches = $productMatches[0];
				$productMatches = substr( $productMatches, 0, strlen($productMatches)-4 );
				echo '<a href="products.php?product='.$productMatches.'"> '.$productMatches.' </a> <br />';
			}
			else
			{
				$matches = $matches[0];
				if( $matches == "checkout.php" )
				{ continue; }
				if( $matches == "index.php" )
				{  $matches = "home.php";  }
				echo '<a href="'.$matches.'"> '.ucwords( substr( $matches, 0, strlen($matches)-4 ) ).' </a> <br />';
			}
		}
	}

	echo '</div>';

	include_once('footer.inc');
?>