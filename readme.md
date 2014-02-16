L4 Support
==========

> Some Helper classes and functions for Laravel 4

## Repository

@todo - add notes about repository class

## Programatic element generation

> HTML::element( $element = 'div', $attributes = [], $content = null )


I find it messy to create HTML elements programatically in PHP - either in classes, or in views. Hence I have added an HTML macro to do just this.

Simply put, it will generate an html element. For example:

```php
echo HTML::element( 'a', array( 'href' => 'google.com' ) );
```
will output
```html
<a href="google.com">
```

if the third argument, `$content`, is provided, it will create the whole element - ie.

```php
echo HTML::element( 'a', array( 'href' => 'google.com' ), 'Go To Google' );
```
will output
```html
<a href="google.com">Go To Google</a>
```

Bear in mind that the third argument will treat an empty string as something to be rendered - so make sure you are deliberate about what you pass to it.

It will also concatenate second-level arrays provided to the second argument. For example:

```php
echo HTML::element( 'div', array( 'class' => array( 'top', 'centre', 'highlight' ) ) )
```
will output
```html
<div class="top centre highlight">
```

This way, you can programmatically add/remove classes, and other attribute elements using arrays (much easier and tidier in PHP that strings with preceding and following spaces), then pass them to this function to process.
