<?php
// �w�b�_�[�s�ݒ�
echo pack('C*',0xEF,0xBB,0xBF);
$this->Csv->addRow($th);
foreach($td as $t) {
	foreach ( $t['Event'] as $d )
	{
		$this->Csv->addField($d);
	}
	// �s�̏I����錾
	$this->Csv->endRow();
}
$this->Csv->setFilename($filename);



//echo $this->Csv->render(true, 'sjis', 'utf-8');
echo $this->Csv->render(true);
?>