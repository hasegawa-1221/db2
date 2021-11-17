<?php
App::uses('FormHelper', 'View/Helper');
App::uses('HtmlHelper', 'View/Helper');
class ExFormHelper extends FormHelper {
	public $helpers = array('Form');
	
	// editor quill ‚ð•\Ž¦
	// @$target = string
	// @$options = array
	// $options
	// 		id
	// 		placeholder
	// 		class
	// 		height —á) 300px
	public function quill ($target = '', $options = array())
	{
		$id = Inflector::camelize(str_replace('.', '_', $target));
		$placeholder = '';
		$class = '';
		$height = '300px';
		$value = '';
		foreach ($options as $key => $val)
		{
			if ($key == 'id')
			{
				$id = $val;
			}
			if ($key == 'placeholder')
			{
				$placeholder = $val;
			}
			if ($key == 'class')
			{
				$class = $val;
			}
			if ($key == 'height')
			{
				$height = $val;
			}
			if ($key == 'value')
			{
				$value = $val;
			}
		}
		
		$editor_id = 'editor_' . $id;
		
$script	= '';
$script .= <<< EOM
		<div id="{$editor_id}" style="height:{$height}"></div>
		<script type="text/javascript">
		var quill = new Quill('#{$editor_id}', {
			modules: {
			toolbar: [
					['bold', 'italic', 'underline', 'strike'],        // toggled buttons
					[{ 'list': 'ordered'}, { 'list': 'bullet' }],
					[{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
					[{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
					[{ 'direction': 'rtl' }],                         // text direction
					[{ 'size': ['10px', '12px', '14px', '16px', '18px', '20px', '22px', '24px', '28px', '32px', '36px', '42px', '48px', '54px', '60px', '66px', '72px', '80px', '88px', '96px', '104px', '112px', '120px'] }],  // custom dropdown
					[{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
					[{ 'font': [] }],
					[{ 'align': [] }],
					['image'],

					['clean']
				]
			},
			placeholder: '{$placeholder}',
			theme: 'snow'  // or 'bubble'
		});
		
		quill.root.innerHTML = "{$value}";
		
		var form = document.querySelector('form');
		form.onsubmit = function() {
			var textarea = document.querySelector('#{$id}');
			textarea.value = quill.root.innerHTML;
		};
		</script>
EOM;
$script .= $this->Form->input($target, array('type' => 'hidden', 'class' => $class));
		return $script;
	}
	
	
}
