<?php
	$productArray = array( 'price' => 59.95 );
	if( $_SESSION['loggedIn'] == "true" )
	{
		if( $productArray['price'] <= 40 )
		{
			$productArray['price'] = number_format( $productArray['price'] * ( 1 - $_SESSION['customer'][6] / 100 ), 2 );
		}
		if( $productArray['price'] > 40 && $productArray['price'] <= 50)
		{
			$productArray['price'] = number_format( $productArray['price'] * ( 1 - $_SESSION['customer'][7] / 100 ), 2 );
		}
		if( $productArray['price'] > 50 )
		{
			$productArray['price'] = number_format( $productArray['price'] * ( 1 - $_SESSION['customer'][8] / 100 ), 2 );
		}
	}
		$productArray = array( 'price' => $productArray['price'], 
			'shortDesc' => "3,000 pieces 73cm x 102cm $".$productArray['price'], 
			'heading' => "白虎図",
			'longDesc' => "白 means white.  虎 means tiger.  図 picture or graph.  So 白虎図 literally means picture of a white tiger.  </p>  <p>  This is a 
			3,000 piece jigsaw puzzle that you'll see is mostly rather monochrome, providing an extreme challenge for puzzle lovers that are bored of the simpler stuff
			.");
?>