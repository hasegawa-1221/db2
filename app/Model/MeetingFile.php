<?php
class MeetingFile extends AppModel {

	public $actsAs = array(
		'Containable',
		'Upload.Upload' => array(
			'file' => array(
				'fields' => array(
					'dir' => 'file_dir'
				),
				'thumbnails' => false,
				'maxSize' => 2097152,
				'nameCallback' => 'rename_file',
			)
		)
	);
	
	public $belongsTo = array(
		'Meeting' => array(
			'className' => 'Meeting',
			'foreignKey' => 'meeting_id'
		),
	);

	public function rename_file ($field, $filename, $data, $option)
	{
		$this->data[$this->name][$field . '_org'] = $filename;
		return hash("md5", $filename) . "." . pathinfo( $filename, PATHINFO_EXTENSION );
	}
}