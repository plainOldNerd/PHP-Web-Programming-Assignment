<?php
	$productArray = array( 'price' => 45.95 );
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
			'shortDesc' => "1,000 pieces 50cm x 75cm $".$productArray['price'],
			'heading' => "桜風",
			'longDesc' => "桜 reads as sakura, meaning Cherry Blossoms.  風 means wind. I suppose this should mean Cherry Blossom trees blowing in the wind, but according 
					to a dictionary I often use, it is read as Ooka, which is a girl's name.  </p>  <p>  This is a 1,000 piece glow-in-the-dark picture. Keep the 
					lights in your room on for long enough for it to charge, and when you turn the lights off you'll see the Cherry Blossom petals illuminate the room.
					It's very pretty; A great decoration when framed." );
?>