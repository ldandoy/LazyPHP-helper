<?php
/**
 * File Helper\Form.php
 *
 * @category System
 * @package  Netoverconsulting
 * @author   Loïc Dandoy <ldandoy@overconsulting.net>
 * @license  GNU
 * @link     http://overconsulting.net
 */

namespace Helper;

use Datetime;

/**
 * Class gérant les Forms du site
 *
 * @category System
 * @package  Netoverconsulting
 * @author   Loïc Dandoy <ldandoy@overconsulting.net>
 * @license  GNU
 * @link     http://overconsulting.net
 */
class Form
{
    private static $commonParams = array(
        'name',
        'id',
        'label',
        'class',
        'value',
        'model',
        'autocomplete',
        'placeholder',
        'readOnly',
        'help',
        'helpHtml',
        'error',
        'errorClass',
        'errorHtml',
        'noBootstrapCol'
    );

    public static $noBootstrapCol = false;

    /**
     * Parse and check common params
     *
     * @param mixed $params
     *
     * @return mixed
     */
    private static function parseParams($params = array())
    {
        $p = array();

        $name = isset($params['name']) ? $params['name'] : '';
        $p['name'] = $name;

        $id = isset($params['id']) ? $params['id'] : $name;
        $p['id'] = $id;

        $p['label'] = isset($params['label']) ? $params['label'] : '';

        if (isset($params['value'])) {
            $p['value'] = $params['value'];
        } else if (isset($params['model']['value'])) {
            $p['value'] = $params['model']['value'];
        } else {
            $p['value'] = '';
        }

        $p['autocomplete'] = isset($params['autocomplete']) ? ' autocomplete="'.$params['autocomplete'].'"' : '';

        $p['placeholder'] = isset($params['placeholder']) ? $params['placeholder'] : '';

        $readOnly = isset($params['readOnly']) ? (bool)$params['readOnly'] : false;
        if ($readOnly) {
            $p['readOnly'] = ' readonly="readonly"';
        } else {
            $p['readOnly'] = '';
        }

        if (isset($params['help']) && $params['help'] != '') {
            $p['helpHtml'] = '<div class="help-block">'.$params['help'].'</div>';
        } else {
            $p['helpHtml'] = '';
        }

        if (isset($params['error'])) {
            $error = $params['error'];
        } else if (isset($params['model']['error'])) {
            $error = $params['model']['error'];
        } else {
            $error = '';
        }
        if ($error != '') {
            $p['errorClass'] = ' is-invalid';
            $p['errorHtml'] = '<div class="invalid-feedback">'.$error.'</div>';
        } else {
            $p['errorClass'] = '';
            $p['errorHtml'] = '';
        }

        $class = (isset($params['class']) ? $params['class'] : '').$p['errorClass'];
        $p['class'] = rtrim(' '.$class);

        return array_merge($params, $p);
    }

    /**
     * Extract "others" attributes
     *
     * @param mixed $params
     * @param string[] $excludedAttributes
     *
     * @return string
     */
    private static function otherAttributes($params = array(), $excludedAttributes = array())
    {
        $attributes = '';

        $excludedAttributes = array_merge(
            self::$commonParams,
            $excludedAttributes
        );

        foreach ($params as $k => $v) {
            if (!in_array($k, $excludedAttributes)) {
                $attributes .= ' '.$k.'="'.$v.'"';
            }
        }

        return $attributes;
    }

    /**
     * Generate form open tag
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function open($params = array())
    {
        self::$noBootstrapCol = isset($params['noBootstrapCol']) ? (bool)$params['noBootstrapCol'] : false;
        unset($params['noBootstrapCol']);

        $params = self::parseParams($params);

        $action = isset($params['action']) ? ' action="'.$params['action'].'"' : '';

        $html = '<form id="'.$params['id'].'" method="post"'.$action.' class="form'.$params['class'].'" enctype="multipart/form-data">';
        return $html;
    }

    /**
     * Generate form close tag
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function close($params = array())
    {
        $params = self::parseParams($params);

        $html = '</form>';

        self::$noBootstrapCol = false;

        return $html;
    }

    /**
     * Generate form-group
     *
     * @param mixed $params
     *
     * @return string
     */
    private static function formGroup($input, $params = array())
    {
        if (isset($params['label']) && $params['label'] != '') {
            $html =
                '<div class="form-group'.(self::$noBootstrapCol ? '' : ' row').'">'.
                    '<label for="'.$params['id'].'" class="col-form-label col-form-label-sm'.(self::$noBootstrapCol ? '' : ' col-sm-2').'">'.$params['label'].'</label>'.
                    (self::$noBootstrapCol ? '' : '<div class="col-sm-10">').
                        $input.
                        $params['errorHtml'].
                        $params['helpHtml'].
                    (self::$noBootstrapCol ? '' : '</div>').
                '</div>';
        } else {
            $html =
                '<div class="form-group'.(self::$noBootstrapCol ? '' : ' row').'">'.
                    (self::$noBootstrapCol ? '' : '<div class="col-sm-12">').
                        $input.
                        $params['errorHtml'].
                        $params['helpHtml'].
                    (self::$noBootstrapCol ? '' : '</div>').
                '</div>';
        }

        return $html;
    }

    /**
     * Generate input hidden
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function hidden($params = array())
    {
        $params = self::parseParams($params);

        $otherAttributes = self::otherAttributes($params);

        $html = '<input type="hidden" id="'.$params['id'].'" name="'.$params['name'].'" value="'.$params['value'].'" class="form-control form-control-sm'.$params['class'].'" />';

        return $html;
    }

    /**
     * Generate input text
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function text($params = array())
    {
        $params = self::parseParams($params);

        $otherAttributes = self::otherAttributes($params);

        $input = '<input type="text" id="'.$params['id'].'" name="'.$params['name'].'" value="'.$params['value'].'" class="form-control form-control-sm'.$params['class'].'" placeholder="'.$params['placeholder'].'"'.$params['readOnly'].$params['autocomplete'].$otherAttributes.' />';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input password
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function password($params = array())
    {
        $params = self::parseParams($params);

        $otherAttributes = self::otherAttributes($params);

        $input = '<input type="password" id="'.$params['id'].'" name="'.$params['name'].'" value="'.$params['value'].'" class="form-control form-control-sm'.$params['class'].'" placeholder="'.$params['placeholder'].'"'.$params['readOnly'].$params['autocomplete'].$otherAttributes.' />';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate textarea
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function textarea($params = array())
    {
        $params = self::parseParams($params);

        $cols = isset($params['cols']) ? $params['cols'] : '';
        $rows = isset($params['rows']) ? $params['rows'] : '5';

        $otherAttributes = self::otherAttributes($params, array('cols', 'rows'));

        $input = '<textarea id="'.$params['id'].'" name="'.$params['name'].'" cols="'.$cols.'" rows="'.$rows.'" class="form-control form-control-sm'.$params['class'].'" placeholder="'.$params['placeholder'].'"'.$params['readOnly'].$otherAttributes.'>'.$params['value'].'</textarea>';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate select
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function select($params = array())
    {
        $params = self::parseParams($params);

        $options = isset($params['options']) ? $params['options'] : array();

        $multiple = isset($params['multiple']) && $params['multiple'] == '1' ? ' multiple="multiple"' : '';

        $otherAttributes = self::otherAttributes($params, array('options', 'multiple'));

        $input =
            '<select id="'.$params['id'].'" name="'.$params['name'].'"'.$multiple.' class="form-control form-control-sm'.$params['class'].'"'.$otherAttributes.'>';
        foreach ($options as $option) {
            if ((is_array($params['value']) && in_array($option['value'], $params['value'])) || $option['value'] == $params['value']) {
                $selected = ' selected="selected"';
            } else {
                $selected = '';
            }
            $input .= '<option value="'.$option['value'].'"'.$selected.'>'.$option['label'].'</option>';
        }
        $input .=
            '</select>';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input checkbox
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function checkbox($params = array())
    {
        $params = self::parseParams($params);

        $checked = $params['value'] == '1' ? ' checked="checked"' : '';

        $otherAttributes = self::otherAttributes($params);

        $disabledClass = $params['readOnly'] != '' ? ' disabled' : '';

        $input =
            '<div class="form-check">'.
                '<label for="'.$params['id'].'" class="form-check-label'.$disabledClass.'">';
        if ($params['readOnly'] != '') {
            $input .=
                '<input value="1"'.$checked.' disabled="disabled" type="checkbox" class="'.$params['class'].' form-check-input" />'.
                '<input id="'.$params['id'].'" name="'.$params['name'].'" type="hidden" value="'.$params['value'].'"'.$otherAttributes.' />';
        } else {
            $input .=
                '<input id="'.$params['id'].'" name="'.$params['name'].'" value="1"'.$checked.' type="checkbox" class="'.$params['class'].' form-check-input"'.$otherAttributes.' />&nbsp;';
        }
        $input .=
                '</label>'.
            '</div>';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input checkboxgroup
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function checkboxgroup($params = array())
    {
        $params = self::parseParams($params);

        $options = isset($params['options']) ? $params['options'] : array();

        $isInline = isset($params['inline']) ? $params['inline'] == '1' : false;
        $inlineClass = $isInline ?' form-check-inline' : '';

        $otherAttributes = self::otherAttributes($params, array('options', 'inline'));

        $value = is_array($params['value']) ? $params['value'] : explode(';', $params['value']);

        $disabledClass = $params['readOnly'] != '' ? ' disabled' : '';

        $input = '';
        foreach ($params['options'] as $option) {
            if (is_array($value) && in_array($option['value'], $value)) {
                $checked = ' checked="checked"';
                $readOnlyValue = $option['value'];
            } else {
                $checked = '';
                $readOnlyValue = '';
            }
            $inputId = $params['id'].'_'.$option['value'];
            $input .=
                '<div class="form-check'.$inlineClass.$disabledClass.'">'.
                    '<label for="'.$inputId.'" class="form-check-label">';
            if ($params['readOnly'] != '') {
                $input .=
                    '<input value="'.$option['value'].'"'.$checked.' type="checkbox" class="'.$params['class'].' form-check-input"'.$otherAttributes.' />&nbsp;'.$option['label'];
            } else {
                $input .=
                    '<input id="'.$inputId.'" name="'.$params['name'].'[]" value="'.$option['value'].'"'.$checked.' type="checkbox" class="'.$params['class'].' form-check-input"'.$otherAttributes.' />&nbsp;'.$option['label'];
            }
            $input .=
                    '</label>'.
                '</div>';
            if ($params['readOnly'] != '') {
                $input .=
                    '<input id="'.$inputId.'" name="'.$params['name'].'" type="hidden" value="'.$readOnlyValue.'"'.$otherAttributes.' />';
            }
        }

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input radiogroup
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function radiogroup($params = array())
    {
        $params = self::parseParams($params);

        $options = isset($params['options']) ? $params['options'] : array();

        $isInline = isset($params['inline']) ? $params['inline'] == '1' : false;
        $inlineClass = $isInline ?' form-check-inline' : '';

        $otherAttributes = self::otherAttributes($params, array('options'));

        $value = is_array($params['value']) ? $params['value'] : explode(';', $params['value']);

        $disabledClass = $params['readOnly'] != '' ? ' disabled' : '';

        $input = '';
        foreach ($params['options'] as $option) {
            if (is_array($value) && in_array($option['value'], $value) || $option['value'] == $value) {
                $checked = ' checked="checked"';
                $readOnlyValue = $option['value'];
            } else {
                $checked = '';
                $readOnlyValue = '';
            }
            $inputId = $params['id'].'_'.$option['value'];
            $input .=
                '<div class="form-check'.$inlineClass.$disabledClass.'">'.
                    '<label for="'.$inputId.'" class="form-check-label">';
            if ($params['readOnly'] != '') {
                $input .=
                    '<input value="'.$option['value'].'"'.$checked.' type="radio" class="'.$params['class'].' form-check-input"'.$otherAttributes.' />&nbsp;'.$option['label'];
            } else {
                $input .=
                    '<input id="'.$inputId.'" name="'.$params['name'].'" value="'.$option['value'].'"'.$checked.' type="radio" class="'.$params['class'].' form-check-input"'.$otherAttributes.' />'.$option['label'];
            }
            $input .=
                    '</label>'.
                '</div>';
            if ($params['readOnly'] != '') {
                $input .=
                    '<input id="'.$inputId.'" name="'.$params['name'].'" type="hidden" value="'.$readOnlyValue.'"'.$otherAttributes.' />';
            }
        }

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input file
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function file($params = array())
    {
        $params = self::parseParams($params);

        $thumbnail = isset($params['thumbnail']) ? $params['thumbnail'] : '1';

        $url = $params['value'] != '' ? $params['value']->url : '';

        if ($thumbnail == '1' && $url != '') {
            $thumbnailHtml = '<i class="fa fa-file-o"> '.$url.'</i>';
        } else {
            $thumbnailHtml = '';
        }

        $otherAttributes = self::otherAttributes($params, array('thumbnail'));

        $input =
            '<input type="hidden" id="_'.$params['id'].'_" name="_'.$params['name'].'_" value="'.$url.'" />'.
            '<input type="file" id="'.$params['id'].'" name="'.$params['name'].'" class="form-control form-control-sm'.$params['class'].'"'.$params['readOnly'].$otherAttributes.' />'.
            $thumbnailHtml;

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input file for image
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function image($params = array())
    {
        $params = self::parseParams($params);

        $thumbnail = isset($params['thumbnail']) ? $params['thumbnail'] : '1';

        $url = $params['value'] != '' ? $params['value']->url : '';

        if ($thumbnail == '1' && $url != '') {
            $thumbnailHtml = '<img src="'.$url.'" class="input-media-image" />';
        } else {
            $thumbnailHtml = '';
        }

        $otherAttributes = self::otherAttributes($params, array('thumbnail'));

        $input =
            '<input type="hidden" id="_'.$params['id'].'_" name="_'.$params['name'].'_" value="'.$url.'" />'.
            '<input type="file" id="'.$params['id'].'" name="'.$params['name'].'" class="form-control form-control-sm'.$params['class'].'"'.$params['readOnly'].$otherAttributes.' data-file-type="image" />'.
            $thumbnailHtml;

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input file for video
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function video($params = array())
    {
        $params = self::parseParams($params);

        $otherAttributes = self::otherAttributes($params);

        $url = $params['value'] != '' ? $params['value']->url : '';

        $input =
            '<input type="hidden" id="_'.$params['id'].'_" name="_'.$params['name'].'_" value="'.$url.'" />'.
            '<input type="file" id="'.$params['id'].'" name="'.$params['name'].'" class="form-control form-control-sm'.$params['class'].'"'.$params['readOnly'].$otherAttributes.' data-file-type="video" />';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input file for audio
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function audio($params = array())
    {
        $params = self::parseParams($params);

        $otherAttributes = self::otherAttributes($params);

        $url = $params['value'] != '' ? $params['value']->url : '';

        $input =
            '<input type="hidden" id="_'.$params['id'].'_" name="_'.$params['name'].'_" value="'.$url.'" />'.
            '<input type="file" id="'.$params['id'].'" name="'.$params['name'].'" class="form-control form-control-sm'.$params['class'].'"'.$params['readOnly'].$otherAttributes.' data-file-type="audio" />';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input for upload a file
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function upload($params = array())
    {
        $params = self::parseParams($params);

        $type = isset($params['type']) ? $params['type'] : '';

        $otherAttributes = self::otherAttributes($params, array('type'));

        $url = $params['value'] != '' ? $params['value']->url : '';

        if ($url != '') {
            $noFileClass = '';
        } else {
            $noFileClass = ' no-file';
        }

        switch ($type) {
            case 'image':
                $typeIcon = 'file-image-o';
                $thumbnail = '<img src="'.$url.'" class="input-upload-thumbnail" />';
                break;
            case 'video':
                $typeIcon = 'file-video-o';
                $thumbnail = '<i class="fa fa-file-video-o input-upload-thumbnail"></i>';
                break;
            case 'audio':
                $typeIcon = 'file-audio-o';
                $thumbnail = '<i class="fa fa-file-audio-o input-upload-thumbnail"></i>';
                break;
            default:
                $typeIcon = 'file-o';
                $thumbnail = '<i class="fa fa-file-o input-upload-thumbnail"></i>';
                break;
        }

        $input =
            '<div id="input_upload_'.$params['id'].'" class="input-upload'.$noFileClass.'" data-type="'.$type.'" data-input-name="'.$params['name'].'" data-input-id="'.$params['id'].'">'.
                '<input type="hidden" id="'.$params['id'].'" name="'.$params['name'].'" value="" class="form-control form-control-sm" />'.
                '<input type="hidden" id="_'.$params['id'].'_" name="_'.$params['name'].'_" value="'.$url.'" />'.
                '<div class="input-upload-trigger" title="Choisir un fichier">'.
                    $thumbnail.
                    '<div class="input-upload-button"><i class="fa fa-'.$typeIcon.'"></i></div>'.
                '</div>'.
                '<div class="input-upload-actions">'.
                    '<button type="button" class="input-upload-action-del btn btn-danger btn-sm" title="Supprimer"><i class="fa fa-remove"></i></button>'.
                '</div>'.
            '</div>';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input for select a media
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function media($params = array())
    {
        $params = self::parseParams($params);

        $mulitple = isset($params['mulitple']) ? $params['mulitple'] : '0';

        $mediaType = isset($params['mediaType']) ? $params['mediaType'] : '';

        $mediaCategory = isset($params['mediaCategory']) ? $params['mediaCategory'] : '';

        $otherAttributes = self::otherAttributes($params, array('multiple', 'mediaType', 'mediaCategory'));

        $input =
            '<input type="hidden" id="'.$params['id'].'" name="'.$params['name'].'" value="'.$params['value'].'" class="form-control form-control-sm" />'.
            '<input type="hidden" id="'.$params['id'].'_url" name="'.$params['name'].'_url" value="" class="form-control form-control-sm" />'.
            '<input type="hidden" id="'.$params['id'].'_format" name="'.$params['name'].'_format" value="" class="form-control form-control-sm" />'.
            '<div class="input-group">'.
                '<input type="text" id="'.$params['id'].'_display" class="form-control form-control-sm input-media'.$params['class'].'" value="'.$params['value'].'" readonly="readonly"'.$otherAttributes.' />'.
                '<span class="input-group-btn">'.
                    '<button class="btn btn-secondary btn-sm input-media-button" type="button" data-input-id="'.$params['id'].'" data-input-display-id="'.$params['id'].'_display" data-multiple="'.$mulitple.'" data-media-type="'.$mediaType.'" data-media-category="'.$mediaCategory.'"><i class="fa fa-picture-o"></i></button>'.
                '</span>'.
            '</div>';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input for "magicsuggest"
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function datetime($params = array())
    {
        $params = self::parseParams($params);

        $type = isset($params['type']) ? $params['type'] : 'datetime';
        // switch ($type) {
        //     case 'datetime':
        //         $format = 'yyyy-dd-mm hh:ii:ss';
        //         break;
        //     case 'date':
        //         $format = 'yyyy-dd-mm';
        //         break;
        //     case 'time':
        //         $format = 'yyyy-dd-mm hh:ii:ss';
        //         break;
        // }

        $otherAttributes = self::otherAttributes($params, array('type'));

        $input =
            '<input type="hidden" id="'.$params['id'].'" name="'.$params['name'].'" value="'.$params['value'].'" class="form-control form-control-sm" />'.
            '<input type="text" id="'.$params['id'].'_display" class="form-control form-control-sm '.$params['class'].' datetimepicker" value="'.$params['value'].'" readonly="readonly"'.$otherAttributes.' />';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate input for "magicsuggest"
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function magicsuggest($params = array())
    {
        $params = self::parseParams($params);

        $options = isset($params['options']) ? rawurlencode(json_encode($params['options'])) : '';

        $valueField = isset($params['valueField']) ? $params['valueField'] : '';

        $displayField = isset($params['displayField']) ? $params['displayField'] : '';

        $otherAttributes = self::otherAttributes($params, array('options', 'valueField', 'displayField'));

        $input = '<input type="hidden" id="'.$params['id'].'" name="'.$params['name'].'" value="['.str_replace(';', ',', $params['value']).']" class="magicsuggest" data-options="'.$options.'" data-value-field="'.$valueField.'" data-display-field="'.$displayField.'"'.$otherAttributes.' />';

        $html = self::formGroup($input, $params);

        return $html;
    }

    /**
     * Generate submit button
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function submit($params = array())
    {
        $params = self::parseParams($params);

        if ($params['readOnly'] != '') {
            $disabled = ' disabled="disabled"';
        } else {
            $disabled = '';
        }

        $formId = isset($params['formId']) ? $params['formId'] : '';

        $label = $params['label'];

        $icon = isset($params['icon']) ? $params['icon'] : '';
        if ($icon != '') {
            $icon = '<i class="fa fa-'.$icon.'"></i>';
            if ($label != '') {
                $icon .= '&nbsp;';
            }
        }
        $label = $icon.$label;

        $otherAttributes = self::otherAttributes($params, array('formId', 'icon'));

        $html =
            '<div class="form-group row">'.
                (self::$noBootstrapCol ? '' : '<div class="col-sm-12">').
                    '<button id="'.$params['id'].'" name="'.$params['name'].'"'.$disabled.' type="submit" value="'.$params['value'].'" form="'.$formId.'" class="btn'.$params['class'].'"'.$otherAttributes.'>'.$label.'</button>'.
                (self::$noBootstrapCol ? '' : '</div>').
            '</div>'.
            '<div class="clearfix"></div>';

        return $html;
    }

    /**
     * Generate input datetimepicker
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function datetimepicker($params = array())
    {
        $params = self::parseParams($params);

        $otherAttributes = self::otherAttributes($params);

        $input = '<input type="text" id="'.$params['id'].'" name="'.$params['name'].'" value="'.$params['value'].'" class="form-control datetimepicker form-control-sm'.$params['class'].'" placeholder="'.$params['placeholder'].'"'.$params['readOnly'].$params['autocomplete'].$otherAttributes.' />';

        $html = self::formGroup($input, $params);

        return $html;
    }
}
