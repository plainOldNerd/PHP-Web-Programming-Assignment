var qty = 1;
var totalPrice;

function plusScript( price )
{
   qty = parseInt( document.getElementById( "quantity" ).value );
   qty++;
   document.getElementById( "quantity" ).value = qty;
   totalPrice = price * qty;
   totalPrice = totalPrice.toFixed( 2 );
   document.getElementById( "totalPrice" ).value = totalPrice;
   document.getElementById( "itemQuantity" ).value = qty;
}

function minusScript( price )
{
   qty = parseInt( document.getElementById( "quantity" ).value );
   qty--; 
   if( qty < 0 )
   {
      qty = 0;
   }
   document.getElementById( "quantity" ).value = qty;
   totalPrice = price * qty;
   totalPrice = totalPrice.toFixed( 2 );
   document.getElementById( "totalPrice" ).value = totalPrice;
   document.getElementById( "itemQuantity" ).value = qty;
}

function quantityChanged( price )
{
   var quantity = document.getElementById( "quantity" ).value;

   if( isNaN( quantity ) )
   {
      document.getElementById( "quantity" ).value = qty;
   }
   else
   {
      if( parseInt( quantity ) >= 0 )
      {
         qty = parseInt( quantity );
         document.getElementById( "quantity" ).value = qty;
         totalPrice = price * qty;
         totalPrice = totalPrice.toFixed( 2 );
         document.getElementById( "totalPrice" ).value = totalPrice;
      }
      else
      {
         document.getElementById( "quantity" ).value = qty;
      }
   }
   document.getElementById( "itemQuantity" ).value = qty;
}

function checkPhone()
{
	var phone = document.getElementById( "phoneNo" ).value;
	var phonePattern = new RegExp( /^(\+|\([0-9]{2}\)|[0-9]{1})[0-9 ]+$/ );
	if( phonePattern.test( phone ) == true )
	{
		var i;
		for( i = 0; i < phone.length-1; i++ )
		{
			if( phone[i] == ' ' && phone[i+1] == ' '  )
			{	
				$("#errorMessage").show();
				break;
			}
		}
		if( i == phone.length-1 )
		{
			$("#errorMessage").hide();
		}
	}
	else
	{
		$("#errorMessage").show();
	}
}

function validCCformat()
{
	var creditCard = document.getElementById( "creditCard" ).value;
	var CCPattern = new RegExp( /^[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]{1}[\- ]?[0-9]?[\- ]?[0-9]?[\- ]?[0-9]?[\- ]?[0-9]?[\- ]?[0-9]?[\- ]?[0-9]{1}$/ );
	if( CCPattern.test( creditCard ) == true )
	{
		var i;
		for( i = 11; i < creditCard.length-2; i++ )
		{
			if( ( creditCard[i] == ' ' || creditCard[i] == '-' ) && ( creditCard[i+1] == ' ' || creditCard[i+1] == '-' ) )
			{	
				$("#invalidCC").show();
				break;
			}
		}
		if( i == creditCard.length-2 )
		{
			$("#invalidCC").hide();
		}
	}
	else
	{
		$("#invalidCC").show();
	}
}

function notExpiredYet()
{
	var thisMonth = new Date().getMonth()+1;
	var thisYear = new Date().getFullYear();
	var expiryMonth = document.getElementById( 'month' ).value;
	var expiryYear = document.getElementById( 'year' ).value;

	if( expiryYear == thisYear && expiryMonth < thisMonth )
	{
		$("#cardExpired").show();
	}
	else
	{
		$("#cardExpired").hide();
	}
}