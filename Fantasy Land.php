<?php
	$productArray = array( 'price' => 46.95 );
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
			'shortDesc' => "960 pieces 30.5 cm diameter $".$productArray['price'], 
			'heading' => "ファンタジーランド",
			'longDesc' => "ファンタジーランド is romanised to fantajii rando which is literally fantasy land in Japanese pronunciation. </p>
  		       			<p>  A glow-in-the-dark puzzle. <br /> Most people start jigsaw puzzles from the edges, but what if there were no edges?!  This puzzle 
					is a sphere so where will you start?  ");
?>