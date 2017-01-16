## My Web Programming Assignment 2

In about the second semester of my Software Engineering degree I had to do Web Programming, where we learned PHP. The idea was to build a website for a business that an imaginary client is running and has asked you to build.

This GitHub repo contains the source code for [that website]( http://titan.csit.rmit.edu.au/~s2109279/WP/A2/checkout.php).

## Some Criticism

1) There was no need for using databases according to the assignment specification. There was actually a “customers.txt” file given to us on the staff account. In the last day or two I’ve somewhat recreated that file (without it, the page displays an error message. This is **not** an error worth handling programmatically because realistically you’d use a database for customers anyway).

2) I’m not sure why a customer logging out should clear the cart instead of remembering the cart for the next session. But without databases involved that seems easier.

## Some Good Points

1) We were told that the Products page and Cart must be dynamically populated, to test our coding abilities. You can test the Cart by adding more items. You will see in the code that any PHP files containing product information start with an uppercase letter, while other PHP files start with a lowercase letter. This way, with regex testing, dynamically displaying the Products page (updating, etc.) becomes easy.

2) We were told that in the “customers.txt” file there was percentage discounts to be applied for customers that logged in, depending on the price range of a particular product. You will see these applied in the website if you log in as Alice Liddell.

3) Just to add more PHP code, and a little JavaScript, we were told to pre-fill details for logged in customers. Also to set the current month and year for credit card expiry on the Checkout page, and to have the years be a list 10 years long.

4) We were encouraged to implement the [Luhn algorithm](https://en.wikipedia.org/wiki/Luhn_algorithm) to validate credit card numbers (impleted here in checkout.php).

## Other Points

1) I prefilled the login fields with values from the “customers.txt” file for ease of testing purposes. I have left them on the website to this day for much the same reason.

2) Not that I’m a graphic designer or anything, but I made that logo in the top left corner of the page myself. 
