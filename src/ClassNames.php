<?php
/**
 * Simple PHP utility class for conditionally joining class names together.
 *
 * @package micropackage/classnames
 * @license GPL-3.0-or-later
 */

namespace Micropackage\ClassNames;

/**
 * ClassNames
 */
class ClassNames {
	/**
	 * ClassNames array.
	 *
	 * @var array
	 */
	private $classnames = [];

	/**
	 * Constructor
	 *
	 * @see  ClassNames::add
	 * @uses ClassNames::add
	 *
	 * @param mixed ...$args String or array of classNames.
	 */
	public function __construct( ...$args ) {
		$this->add( ...$args );
	}

	/**
	 * Add classNames.
	 *
	 * This method accepts any number of arguments. Available argument types
	 * are string or array. ClassNames passed as a string arguments or an array of
	 * strings will be joined unconditionally.
	 *
	 * It's also possible to pass an array in form of [ string => bool ] where the
	 * key is a className and the value is a condition.
	 *
	 * @uses ClassNames::parse
	 *
	 * @param mixed ...$args String or array of classNames.
	 * @return array All added classes.
	 */
	public function add( ...$args ) : array {
		$this->parse( $args );

		return $this->classnames;
	}

	/**
	 * Remove classnames from current set.
	 *
	 * @param  mixed ...$args Strings or arrays of classnames.
	 * @return array All classnames after removal.
	 */
	public function remove( ...$args ) : array {
		foreach ( $args as $value ) {
			if ( is_array( $value ) ) {
				$this->remove( ...$value );
				continue;
			}

			if ( is_string( $value ) && in_array( $value, $this->classnames, true ) ) {
				$key = array_search( $value, $this->classnames, true );

				unset( $this->classnames[ $key ] );
			}
		}

		return $this->classnames;
	}

	/**
	 * Parse classNames.
	 *
	 * @param array $classnames ClassNames array.
	 */
	private function parse( array $classnames ) : void {
		foreach ( $classnames as $key => $value ) {
			if ( is_array( $value ) ) {
				$this->parse( $value );
				continue;
			}

			$classToAdd = false;

			if ( is_int( $key ) && is_string( $value ) ) {
				$classToAdd = $value;
			} elseif ( is_string( $key ) ) {
				if ( (bool) $value ) {
					$classToAdd = $key;
				} else {
					$this->remove( $key );
				}
			}

			if ( $classToAdd && ! in_array( $classToAdd, $this->classnames, true ) ) {
				$this->classnames[] = $classToAdd;
			}
		}
	}

	/**
	 * Build className string from added classNames.
	 *
	 * @return string Classname string.
	 */
	public function build() : string {
		return implode( ' ', array_unique( $this->classnames ) );
	}

	/**
	 * Build className string from added classNames.
	 *
	 * @param  string $before Optional prefix.
	 * @param  string $after  Optional appendix.
	 * @return string Class attribute string.
	 */
	public function buildAttribute( string $before = '', string $after = '' ) : string {
		$classname = $this->build();

		if ( $classname ) {
			return sprintf(
				'%1$sclass="%2$s"%3$s',
				$before,
				$classname,
				$after
			);
		}

		return '';
	}

	/**
	 * Get classname string.
	 *
	 * @param  mixed ...$args Strings or arrays of classnames.
	 * @return string Classname string.
	 */
	public static function get( ...$args ) : string {
		return ( new static( ...$args ) )->build();
	}

	/**
	 * Get class attribute string.
	 *
	 * @param  mixed ...$args Strings or arrays of classnames.
	 * @return string Class attribute string.
	 */
	public static function getAttribute( ...$args ) : string {
		$classnames = [];
		$before     = '';
		$after      = '';

		foreach ( $args as $arg ) {
			if (
				is_array( $arg ) &&
				(
					array_key_exists( 'before', $arg ) && is_string( $arg['before'] ) ||
					array_key_exists( 'after', $arg ) && is_string( $arg['after'] )
				)
			) {
				if ( array_key_exists( 'before', $arg ) ) {
					$before = $arg['before'];
				}

				if ( array_key_exists( 'after', $arg ) ) {
					$after = $arg['after'];
				}

				continue;
			}

			$classnames[] = $arg;
		}

		return ( new static( ...$classnames ) )->buildAttribute( $before, $after );
	}

	/**
	 * Print classname string.
	 *
	 * @param  mixed ...$args Strings or arrays of classnames.
	 */
	public static function print( ...$args ) : void {
		echo static::get( ...$args );
	}

	/**
	 * Print class attribute string.
	 *
	 * @param  mixed ...$args Strings or arrays of classnames.
	 */
	public static function printAttribute( ...$args ) : void {
		echo static::getAttribute( ...$args );
	}

	/**
	 * Magic method converting instance to string.
	 * This method allows to use a class like this:
	 *
	 *     `echo new ClassNames( 'classname' );`
	 *
	 * @return string ClassName string.
	 */
	public function __toString() : string {
		return $this->build();
	}
}
