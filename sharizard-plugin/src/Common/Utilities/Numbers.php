<?php

declare( strict_types=1 );

namespace SharizardWordpress\Common\Utilities;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Numbers::class ) ) {
	class Numbers {

		/**
		 * Round a numeric value up to the nearest decimal, such as up to the nearest 10 cents (without currency symbol).
		 *
		 * Any zero value will always return a '0' integer, never '0.0' or '0.00' as those are not valid floats, and we
		 * do not return strings.
		 * Look out for negative numbers. Rounding *up* '1.1' to zero places becomes '2', but rounding *up* '-1.9' to
		 * zero places becomes '-1'.
		 *
		 * @link http://php.net/manual/en/function.ceil.php#50448 Source of this code.
		 *
		 * @param string|float|int $value  A numeric value, whether a string, float, or integer.
		 *                                 If zero, result will be zero regardless of $places.
		 * @param int              $places The positive number of digits to round to, such as 1 for the nearest 10 cents
		 *                                 or 2 for the nearest penny.
		 *
		 * @return float|int Integer if an empty $value or $places is zero, else float.
		 */
		public function round_up( $value, int $places = 0 ) {
			$value = (float) $value;

			// Avoid dividing by zero.
			if ( empty( $value ) ) {
				return 0;
			}

			$places = absint( $places );

			$multiplier = pow( 10, $places );

			$result = ceil( $value * $multiplier ) / $multiplier;

			// Rounding to zero places expects integer (e.g. '18'), not float (e.g. '18.0').
			if( 0 === $places ) {
				return (int) $result;
			}

			return $result;
		}

		/**
		 * Given a number, round up to the given integer interval.
		 *
		 * Useful to round up to the next 15 minutes, such as rounding 63 minutes up to 75 minutes (60 minutes + 15 minutes).
		 *
		 * @param int|float|string $value    The integer, float, or numeric string to round up to the next interval.
		 * @param int              $interval The interval to round up to, such as 15. Set to 1 to get the same thing as just
		 *                                   using ceil().
		 *
		 * @return int
		 */
		public function round_up_to_next( $value = 0, int $interval = 0 ): int {
			if (
				empty( $value )
				|| ! is_numeric( $value )
				|| ! is_int( $interval )
				|| 0 >= $interval
			) {
				return 0;
			}

			$result = $interval * ceil( $value / $interval );

			return (int) round( $result );
		}
	}
}
