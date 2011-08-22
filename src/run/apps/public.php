<?php
/**
 * Run The Frood in public mode.
 *
 * PHP version 5
 *
 * @category   Module
 * @package    Frood
 * @subpackage Runners/Apps
 * @author     Jens Riisom Schultz <jers@fynskemedier.dk>
 * @since      2011-07-05
 */

$frood = new Frood(
	basename(realpath(dirname(__FILE__) . '/../../../../')), // The name of the module.
	'public'
);

try {
	$frood->dispatch();
} catch (FroodExceptionDispatch $e) {
	header("X-Frood-Message: {$e->getMessage()}", false, 404);
	exit;
}