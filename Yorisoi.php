<?php
	$productArray = array( 'price' => 36.95 );
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
			'shortDesc' => "2-in-1 puzzle           $".$productArray['price']."</p>  <p> 1500 pieces 50cm x 75cm & <br /> 400 pieces 26cm x 38cm", 
			'heading' => "寄り添い",
			'longDesc' => "寄り means to draw near, or to gather in one place.  添い means to stay by one's side.  寄り添い means to nestle close 
					together, and indeed this jigsaw puzzle's English name is Nestling Close.  </p>  <p>  This package contains the same picture in 
					two different sizes and of course two different numbers of pieces in the same package. For each piece, you will need to decide if 
					it belongs to the smaller picture, or larger, and where in the picture it belongs." );

?>