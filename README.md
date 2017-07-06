yii-inlineedit
==============

Provides simple opportunity for replacing model field value with editable textarea. New value sends via AJAX. Several customization available.

Requirements 
=========================

Yii 1.1 or above and jQuery.

Installation
========================= 

To use this extension:

1) copy InlineEdit.php to your components directory

2) check that your config have autoloaded

	'import'=>array(
		...
		'application.components.*',
		...
		
Usage
=========================

Assuming that you have $model - Article activerecord class object with field title and primaryKey = 1 and you need it to be inline editable in your view:

	echo InlineEdit::textField($model, 'title', $model->title);

You can disable such behavior, just echoing $value (for example if you need to show textareas only for admins)

	echo InlineEdit::textField($model, 'title', $model->title, $enable);

in such case inline editable textareas will be shown only if $enable == true.

If you need just string value, without sending it to output set $return to true:

	$output = InlineEdit::textField($model, 'title', $model->title, true, true);

Default ajax url setted to Article/update/1 but you always can change it:

	echo InlineEdit::textField($model, 'title', $model->title, true, false, '/<your new url for ajax save call>/');

Styles
=========================

Adjust textarea style with this classes:

	* .inline-edit-field
	* .inline-edit-field.inactive
    * .inline-edit-field.active
