<?php
class CsvHelper extends AppHelper {

	var $delimiter = ',';
	var $enclosure = '"';
	var $filename = 'Export.csv';
	var $line = array();
	var $buffer;

	function CsvHelper() {
		$this->clear();
	}

	function clear() {
		$this->line = array();
		$this->buffer = fopen('php://temp/maxmemory:'.(5*1024*1024), 'r+');
	}

	function addField($value) {
		$this->line[] = $value;
	}

	function endRow() {
		$this->addRow($this->line);
		$this->line = array();
	}

	function addRow($row) {
		fputcsv($this->buffer, $row, $this->delimiter, $this->enclosure);
	}

	function renderHeaders() {
		header("Content-type:application/vnd.ms-excel");
		header("Content-disposition:attachment;filename=".$this->filename);
	}

	function setFilename($filename) {
		// IEでのファイル名文字化け対策
		$this->filename = mb_convert_encoding($filename, 'sjis', 'utf-8');
		if (strtolower(substr($this->filename, -4)) != '.csv') {
			$this->filename .= '.csv';
		}
	}

	function render($outputHeaders = true, $to_encoding = null, $from_encoding = "auto") {
		if ($outputHeaders) {
			if (is_string($outputHeaders)) {
				$this->setFilename($outputHeaders);
			}
			$this->renderHeaders();
		}
		rewind($this->buffer);
		$output = stream_get_contents($this->buffer);
		if ($to_encoding) {
			$output = mb_convert_encoding($output, $to_encoding, $from_encoding);
		}
		
		//$output = pack('C*',0xEF,0xBB,0xBF). $output;
		
		return $this->output($output);
	}
}
?>