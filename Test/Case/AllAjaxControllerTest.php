<?php
/**
 * All AjaxController plugin tests
 */
class AllAjaxControllerTest extends CakeTestCase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All AjaxController test');

		$path = CakePlugin::path('AjaxController') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}
