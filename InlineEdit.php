<?php

class InlineEdit {
    /**
     * Echoes or returns inline editable textArea field.
     * Can be disabled by setting $enable to false.
     * After user edit, textarea on lost focus (blur) sends post ajax request on
     * url: /<model class name>/update/<model primary key value> (can be 
     * overwritten by setting $updateUrl). Adjust view of textarea with
     * .inline-edit-field, .inline-edit-field.inactive, 
     * .inline-edit-field.active classes.
     * @param CActiveRecord $model model which fields need to be inline editable
     * @param string $field name of field which need to be saved after edit
     * @param strign $value current value of the field (init value for textarea)
     * @param bool $enable
     * @param bool $return whether the rendering result should be returned
     * instead of being displayed to end users
     * @param string $updateUrl ajax POST requests will be sended to this url
     * @return string the rendering result. 
     * Null if the rendering result is not required.
     */  
    public static function textField($model, $field, $value, $enable = true, $return = false, $updateUrl = '/{className}/update/{primaryKey}') {
        if (!$enable)
            $fieldHtml = $value;
        else {
            static $registered;
            if (!isset($registered[get_class($model)][$field]))
                $registered[get_class($model)][$field] = self::registerJS(get_class($model), $field, $updateUrl);
            $fieldHtml = 
                '<textarea id="inline-edit-'.$model->primaryKey.'" class="inline-edit-field inactive '.get_class($model).'-'.$field.'">'
                    .$value.
                '</textarea>';
        }
        if($return)
            return $fieldHtml;
        else
            echo $fieldHtml;
    }
    
    /**
     * Binds .inactive and .active selectors with appropriate functions:
     * class changing on focus and making ajax request on blur. Must be called 
     * once for each unique pair $modelName and $fieldName.
     * @param string $modelName
     * @param string $fieldName
     * @param string $updateUrl
     * @return CClientScript for chaining support
     */
    protected static function registerJS($modelName, $fieldName, $updateUrl) {
        return Yii::app()->clientScript->registerScript(
            'inlineEdit', 
            '$(".'.$modelName.'-'.$fieldName.'.inactive").live("focus", function() {
                $(this).removeClass("inactive");
                $(this).addClass("active");
            });
            $(".'.$modelName.'-'.$fieldName.'.active").live("blur", function() {
                var updateUrl = "'.$updateUrl.'";
                $(this).removeClass("active");
                $(this).addClass("inactive");
                var primaryKey = $(this).attr("id").replace("inline-edit-","");
                updateUrl = updateUrl.replace("{className}", "'.$modelName.'").replace("{primaryKey}", primaryKey);
                var values = {};
                values["'.$modelName.'['.$fieldName.']"] = $(this).val();
                $.ajax({
                    type: "POST",
                    url: updateUrl,
                    data: values
                });
            });',
            CClientScript::POS_READY
        );
    }
}