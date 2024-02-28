<?php
/**
 * Class SampleTest
 *
 * @package Ada_Aba
 */

 use function Db_Helpers\dt_to_sql;

/**
 * Sample test case.
 */
class DateConversionTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_sample() {
		$date = dt_to_sql(DateTime::createFromFormat('Y-m-d\\TH:i:s', '2015-09-01T00:00:00'));
		$this->assertEquals('2015-09-01 00:00:00', $date);
	}
}
