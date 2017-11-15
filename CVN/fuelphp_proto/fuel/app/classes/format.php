<?php
class Format extends \Fuel\Core\Format {
	/**
	 * CSV出力をSJIS-WINで返す
	 * @access public
	 * @param mixed $data
	 * @return string csv(sjis-win)
	 */
	public function to_csv2($data = null, $delimiter = null, $enclose_numbers = null, array $headings = array()){
		$csv = parent::to_csv($data, $delimiter,$enclose_numbers,$headings);
		$csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
		return $csv;
	}
}