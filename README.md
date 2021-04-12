# Block Loader

[![BracketSpace Micropackage](https://img.shields.io/badge/BracketSpace-Micropackage-brightgreen)](https://bracketspace.com)
[![Latest Stable Version](https://poser.pugx.org/micropackage/classnames/v/stable)](https://packagist.org/packages/micropackage/classnames)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/micropackage/classnames.svg)](https://packagist.org/packages/micropackage/classnames)
[![Total Downloads](https://poser.pugx.org/micropackage/classnames/downloads)](https://packagist.org/packages/micropackage/classnames)
[![License](https://poser.pugx.org/micropackage/classnames/license)](https://packagist.org/packages/micropackage/classnames)

<p align="center">
    <img src="https://bracketspace.com/extras/micropackage/micropackage-small.png" alt="Micropackage logo"/>
</p>

## 🧬 About ClassNames

This package contains simple utility class for conditionally joining html classNames. It was inspired by the JavaScript [classnames](https://www.npmjs.com/package/classnames) package.

## 💾 Installation

``` bash
composer require micropackage/classnames
```

## 🕹 Usage

The `Micropackage\ClassNames\ClassNames` class constructor takes any number of arguments which can be a string or an array. String arguments and values of string arrays will be used unconditionally. If an argument is an array with string keys, keys will be used as classnames if the value associated with a given key is truthy.
```php
use Micropackage\ClassNames\ClassNames;

new ClassNames( 'foo', 'bar' ); // => 'foo bar'
new ClassNames( 'foo', [ 'bar' => true ] ); // => 'foo bar'
new ClassNames( [ 'foo' => true, 'bar' => false ] ); // => 'foo'
new ClassNames( 'foo', [ 'foo' => false ] ); // => ''
new ClassNames( [ 'foo', 'bar' => false, 'baz' ] ); // => 'foo baz'
```

### Full example

```php
<?php
/**
 * Example WordPress template using ACF
 */

use Micropackage\ClassNames\ClassNames;

$text_color = get_filed( 'text-color' );

$classnames = new ClassNames(
	'main-hero',
	[
		'has-background'          => get_filed( 'has-background' ), // Conditionally add background class
		"has-{$text_color}-color" => $text_color, // Only add color class if color is not null
	]
);
?>

<div class="<?php echo $classnames; ?>">
	<!-- (...) -->
</div>
```

### Methods

#### `add`

Adds classNames to the current set. Accepts any number of arguments, just like the constructor.

```php
$classnames = new ClassNames( 'foo' );

if ( is_bar() ) {
	$classnames->add( 'bar', [ 'baz' => is_baz() ] );
}
```

##### Returns `array`
All included classnames.

#### `remove`
Removes classNames from the current set. Accepts any number of arguments which can be a string or an array of strings.

```php
$classnames = new ClassNames( 'foo', 'bar', 'baz', 'duck' );

if ( ! is_bar() ) {
	$classnames->remove( 'bar', [ 'baz', 'duck' ] );
}
```

##### Returns `array`
All included classnames.

#### `build`
Creates string from current classNames set.

```php
$classnames = new ClassNames( 'foo', [ 'bar' => true, 'baz' => false ] );

$result = $classnames->build(); // => 'foo bar'
```

##### Returns `string`

#### `buildAttribute`
Creates string with HTML class attribute from current classNames set.

##### Params
`string` $before Optional prefix

`string` $after  Optional suffix

```php
$classnames = new ClassNames( 'foo', [ 'bar' => true, 'baz' => false ] );

$result = $classnames->buildAttribute( ' ', ' tabndex="-1"'); // => ' class="foo bar" tabindex="-1"'
```

##### Returns `string`

### Static methods

#### `ClassName::get`
Accepts arguments like constructor and returns a className string. This is a short equivalent of creating an instance and calling `$instance->build()`.

```php
$result = ClassNames::get( 'foo', [ 'bar' => true, 'baz' => false ] ); // => 'foo bar'
```

##### Returns `string`

#### `ClassName::getAttribute`
Accepts arguments like constructor and returns a class attribute string. This is a short equivalent of creating an instance and calling `$instance->buildAttribute()`.

It's possible to pass an array with keys `before` and/or `after` as one of arguments.

```php
$result = ClassNames::getAttribute(
	'foo',
	[
		'bar' => true,
		'baz' => false
	],
	[
		'before' => 'prefix ',
		'after'  => ' sufix',
	]
); // => 'prefix class="foo bar" sufix'
```

##### Returns `string`

#### `ClassName::print`
Echoes the result of `ClassName::get`.

```php
ClassNames::print( 'foo', [ 'bar' => true, 'baz' => false ] ); // echoes 'foo bar'
```

##### Returns `void`

#### `ClassName::printAttribute`
Echoes the result of `ClassName::getAttribute`.

```php
ClassNames::getAttribute(
	'foo',
	[
		'bar' => true,
		'baz' => false
	],
	[
		'before' => 'prefix ',
		'after'  => ' sufix',
	]
); // echoes 'prefix class="foo bar" sufix'
```

##### Returns `void`


## 📦 About the Micropackage project

Micropackages - as the name suggests - are micro packages with a tiny bit of reusable code, helpful particularly in WordPress development.

The aim is to have multiple packages which can be put together to create something bigger by defining only the structure.

Micropackages are maintained by [BracketSpace](https://bracketspace.com).

## 📖 Changelog

[See the changelog file](./CHANGELOG.md).

## 📃 License

GNU General Public License (GPL) v3.0. See the [LICENSE](./LICENSE) file for more information.

## © Credits

This package was inspired by the JavaScript [classnames](https://www.npmjs.com/package/classnames) by [Jed Watson](https://github.com/JedWatson).
