<?php
class EventFile extends AppModel {

	public $actsAs = array(
		'Containable',
		'Upload.Upload' => array(
			'file' => array(
				'fields' => array(
					'dir' => 'file_dir'
				),
				'thumbnailSizes' => array(
					'thumb150' => '150x150'
				),
				'thumbnailMethod' => 'php',
				'maxSize' => 2097152,
				'nameCallback' => 'rename_file',
			)
		)
	);
	
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id'
		),
	);

	public function rename_file ($field, $filename, $data, $option)
	{
		$this->data[$this->name][$field . '_org'] = $filename;
		return hash("md5", $filename) . "." . pathinfo( $filename, PATHINFO_EXTENSION );
	}
}