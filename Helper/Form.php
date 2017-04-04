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

        $class = isset($params['class']) ? $params['class'] : '';
        $p['class'] = rtrim(' '.$class);

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

        if (isset($params['error'])) {
            $error = $params['error'];
        } else if (isset($params['model']['error'])) {
            $error = $params['model']['error'];
        } else {
            $error = '';
        }
        if ($error != '') {
            $p['errorClass'] = ' has-error';
            $p['errorHtml'] = '<div class="help-block error-block">'.$error.'</div>';
        } else {
            $p['errorClass'] = '';
            $p['errorHtml'] = '';
        }

        return array_merge($params, $p);
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
        $params = self::parseParams($params);

        $formAction = isset($params['formAction']) ? $params['formAction'] : '';

        $html = '<form id="'.$params['id'].'" method="post" action="'.$params['action'].'" class="form'.$params['class'].'" enctype="multipart/form-data">';

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

        return $html;
    }

    /**
     * Generate form-group
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function formGroup($input, $params = array())
    {
        if (isset($params['label'])) {
            $html =
                '<div class="form-group form-group-sm'.$params['errorClass'].'">'.
                    '<label for="'.$params['id'].'" class="col-sm-2 control-label">'.$params['label'].'</label>'.
                    '<div class="col-sm-10">'.
                        $input.
                        $params['errorHtml'].
                    '</div>'.
                '</div>';
        } else {
            $html =
                '<div class="form-group form-group-sm'.$params['errorClass'].'">'.
                    '<div class="col-sm-12">'.
                        $input.
                        $params['errorHtml'].
                    '</div>'.
                '</div>';
        }

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

        $input = '<input type="text" id="'.$params['id'].'" name="'.$params['name'].'" value="'.$params['value'].'" class="form-control'.$params['class'].'" placeholder="'.$params['placeholder'].'"'.$params['readOnly'].$params['autocomplete'].' />';

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

        $input = '<input type="password" id="'.$params['id'].'" name="'.$params['name'].'" value="'.$params['value'].'" class="form-control'.$params['class'].'" placeholder="'.$params['placeholder'].'"'.$params['readOnly'].$params['autocomplete'].' />';

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

        $input = '<textarea id="'.$params['id'].'" name="'.$params['name'].'" cols="'.$cols.'" rows="'.$rows.'" class="form-control'.$params['class'].'" placeholder="'.$params['placeholder'].'"'.$params['readOnly'].'>'.$params['value'].'</textarea>';

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

        $input =
            '<select id="'.$params['id'].'" name="'.$params['name'].'"'.$multiple.' class="form-control'.$params['class'].'">';
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

        $input =
            '<label for="'.$params['id'].'" class="checkbox-inline">';
        if ($params['readOnly'] != '') {
            $input .=
                '<input value="1"'.$checked.' disabled="disabled" type="checkbox" class="'.$params['class'].'" />'.
                '<input id="'.$params['id'].'" name="'.$params['name'].'" type="hidden" value="'.$params['value'].'" />';
        } else {
            $input .=
                '<input id="'.$params['id'].'" name="'.$params['name'].'" value="1"'.$checked.' type="checkbox" class="'.$params['class'].'" />&nbsp;';
        }
        $input .=
            '</label>';

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

        $html =
            '<div class="form-group form-group-sm'.$params['errorClass'].'">'.
                '<label for="'.$params['id'].'" class="col-sm-2 control-label">'.$params['label'].'</label>'.
                '<div class="col-sm-10">';
        foreach ($params['options'] as $option) {
            if (is_array($params['value']) && in_array($option['value'], $params['value'])) {
                $checked = ' checked="checked"';
                $readOnlyValue = $option['value'];
            } else {
                $checked = '';
                $readOnlyValue = '';
            }
            $inputId = $params['id'].'_'.$option['value'];
            $html .=
                    '<label for="'.$inputId.'" class="checkbox-inline">';
            if ($params['readOnly'] != '') {
                $html .=
                        '<input value="'.$option['value'].'"'.$checked.' type="radio" class="'.$class.'" />&nbsp;'.$option['label'];
            } else {
                $html .=
                        '<input id="'.$inputId.'" name="'.$params['name'].'[]" value="'.$option['value'].'"'.$checked.' type="checkbox" class="'.$params['class'].'" />'.$option['label'];
            }
                $html .=
                    '</label>';
            if ($params['readOnly'] != '') {
                $html .=
                    '<input id="'.$inputId.'" name="'.$params['name'].'" type="hidden" value="'.$readOnlyValue.'" />';
            }
        }
        $html .=
                    $params['errorHtml'].
                '</div>'.
            '</div>';
        
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

        $html =
            '<div class="form-group form-group-sm'.$params['errorClass'].'">'.
                '<label for="'.$params['id'].'" class="col-sm-2 control-label">'.$params['label'].'</label>'.
                '<div class="col-sm-10">';
        foreach ($params['options'] as $option) {
            if (is_array($params['value']) && in_array($option['value'], $params['value']) || $option['value'] == $params['value']) {
                $checked = ' checked="checked"';
                $readOnlyValue = $option['value'];
            } else {
                $checked = '';
                $readOnlyValue = '';
            }
            $inputId = $params['id'].'_'.$option['value'];
            $html .=
                '<label for="'.$inputId.'" class="radio-inline">';
            if ($params['readOnly'] != '') {
                $html .=
                    '<input value="'.$option['value'].'"'.$checked.' type="radio" class="'.$class.'" />&nbsp;'.$option['label'];
            } else {
                $html .=
                    '<input id="'.$inputId.'" name="'.$params['name'].'" value="'.$option['value'].'"'.$checked.' type="radio" class="'.$params['class'].'" />'.$option['label'];
            }
            $html .=
                '</label>';
            if ($params['readOnly'] != '') {
                $html .=
                    '<input id="'.$inputId.'" name="'.$params['name'].'" type="hidden" value="'.$readOnlyValue.'" />';
            }
        }
        $html .=
                    $params['errorHtml'].
                '</div>'.
            '</div>';
        
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

        $html =
            '<div class="form-group form-group-sm'.$params['errorClass'].'">'.
                '<label for="'.$params['id'].'" class="col-sm-2 control-label">'.$params['label'].'</label>'.
                '<div class="col-sm-10">'.
                    '<input type="file" id="'.$params['id'].'" name="'.$params['name'].'" class="form-control'.$params['class'].'"'.$params['readOnly'].' />'.
                    $params['errorHtml'].
                '</div>'.
            '</div>';

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

        $html =
            '<div class="form-group form-group-sm'.$params['errorClass'].'">'.
                '<label for="'.$params['id'].'" class="col-sm-2 control-label">'.$params['label'].'</label>'.
                '<div class="col-sm-10">'.
                    '<input type="file" id="'.$params['id'].'" name="'.$params['name'].'" class="form-control'.$params['class'].'"'.$params['readOnly'].' />'.
                    $params['errorHtml'].
                '</div>'.
            '</div>';

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

        $html =
            '<div class="form-group form-group-sm'.$params['errorClass'].'">'.
                '<label for="'.$params['id'].'" class="col-sm-2 control-label">'.$params['label'].'</label>'.
                '<div class="col-sm-10">'.
                    '<input type="file" id="'.$params['id'].'" name="'.$params['name'].'" class="form-control'.$params['class'].'"'.$params['readOnly'].' data-media-type="video" />'.
                    $params['errorHtml'].
                '</div>'.
            '</div>';

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

        $html =
            '<div class="form-group form-group-sm'.$params['errorClass'].'">'.
                '<label for="'.$params['id'].'" class="col-sm-2 control-label">'.$params['label'].'</label>'.
                '<div class="col-sm-10">'.
                    '<input type="file" id="'.$params['id'].'" name="'.$params['name'].'" class="form-control'.$params['class'].'"'.$params['readOnly'].' data-media-type="audio" />'.
                    $params['errorHtml'].
                '</div>'.
            '</div>';

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
        
        $html =
            '<div class="form-group form-group-sm pull-right">'.
                '<div class="col-sm-12">'.
                    '<button id="'.$params['id'].'" name="'.$params['name'].'"'.$disabled.' type="submit" value="'.$params['value'].'" form="'.$formId.'" class="btn'.$params['class'].'">'.$params['label'].'</button>'.
                '</div>'.
            '</div>'.
            '<div class="clearfix"></div>';

        return $html;
    }
}
