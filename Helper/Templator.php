<?php

namespace Helper;

use Helper\TemplatorParser;
use Helper\Html;
use Helper\Form;

class Templator
{
    /**
     * @param mixed $attributes
     * @param mixed $params
     */
    private function getModelValueForInput($attributes, $params)
    {
        if (isset($attributes['model'])) {
            $model = $attributes['model'];
            if (strpos($model, '.') !== false) {
                $a = explode('.', $model, 2);
                if (isset($params[$a[0]])) {
                    $obj = $params[$a[0]];
                    $key = $a[1];

                    $value = isset($obj->$key) ? $obj->$key : '';
                    $error =  isset($obj->errors[$key]) ? $obj->errors[$key] : '';
                } else {
                    return null;
                }
            } else {
                $value = isset($params[$model]) ? $params[$model] : '';
                $error = isset($params['errors'][$model]) ? $params['errors'][$model] : '';
            }
            return array(
                'value' => $value,
                'error' => $error
            );
        } else {
            return null;
        }
    }

    /**
     * @param mixed $attributes
     * @param mixed $params
     */
    private function getOptionsForInput($attributes, $params)
    {
        if (isset($attributes['options'])) {
            $options = $attributes['options'];
            if ($options[0] == '[') {
                $a = explode(';', trim($options, '[]'));
                $options = array();
                foreach ($a as $v) {
                    $b = explode(':', $v);
                    $options[] = array('label' => $b[0], 'value' => $b[1]);
                }
                return $options;
            } else if (isset($params[$options])) {
                return $params[$options];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * @param string $html
     * @param mixed $params
     */
    public function parse($html, $params = array())
    {
        $matchesFunctions = array();
        preg_match_all("/{% *([^}{]*) *%}/", $html, $matchesFunctions, PREG_SET_ORDER);

        if (!empty($matchesFunctions)) {
            $parser = new TemplatorParser();

            foreach ($matchesFunctions as $v) {
                $data = $parser->parse($v[1]);

                //echo '<pre>'.print_r($data,true).'</pre>';

                $tag = $data['tag'];
                $attributes = $data['attributes'];

                if (strpos($tag, 'input_') === 0) {
                    $model = $this->getModelValueForInput($attributes, $params);
                    if ($model !== null) {
                        $attributes['model'] = $model;
                    } else {
                        unset($attributes['model']);
                    }
                }

                if ($tag == 'form_open') {
                    if (isset($attributes['action']) && isset($params[$attributes['action']])) {
                        $attributes['action'] = $params[$attributes['action']];
                    } else {
                        unset($attributes['action']);
                    }
                }

                if ($tag == 'input_select' || $tag == 'input_radiogroup' || $tag == 'input_checkboxgroup') {
                    $options = $this->getOptionsForInput($attributes, $params);
                    if ($options !== null) {
                        $attributes['options'] = $options;
                    } else {
                        unset($attributes['options']);
                    }
                }

                switch ($tag) {
                    case 'link':
                        $replace = Html::link($attributes);
                        break;

                    case 'button':
                        $replace = Bootstrap::button($attributes);
                        break;

                    case 'image':
                        $replace = Html::image($attributes);
                        break;

                    case 'table':
                        $datasetKey = isset($attributes['dataset']) ? $attributes['dataset'] : '';
                        if ($datasetKey != '' && isset($params[$datasetKey])) {
                            $attributes['dataset'] = $params[$datasetKey];
                        } else {
                            unset($attributes['dataset']);
                        }

                        $replace = Html::table($attributes);
                        break;

                    case 'form_open':
                        $replace = Form::open($attributes);
                        break;

                    case 'form_close':
                        $replace = Form::close($attributes);
                        break;

                    case 'input_text':
                        $replace = Form::text($attributes);
                        break;

                    case 'input_password':
                        $replace = Form::password($attributes);
                        break;

                    case 'input_textarea':
                        $replace = Form::textarea($attributes);
                        break;

                    case 'input_select':
                        $replace = Form::select($attributes);
                        break;

                    case 'input_checkbox':
                        $replace = Form::checkbox($attributes);
                        break;

                    case 'input_checkboxgroup':
                        $replace = Form::checkboxgroup($attributes);
                        break;

                    case 'input_radiogroup':
                        $replace = Form::radiogroup($attributes);
                        break;

                    case 'input_file':
                        $replace = Form::file($attributes);
                        break;

                    case 'input_image':
                        $replace = Form::image($attributes);
                        break;

                    case 'input_submit':
                        $replace = Form::submit($attributes);
                        break;
                }

                $html = str_replace($v[0], $replace, $html);

/*                $get = explode(" ", $v[1]);
                $helper = $get[0];
                if (isset($params[$get[1]])) {
                    $valeur = $params[$get[1]];
                } else {
                    $valeur = null;
                }
                $conf = array(
                    'helper'    =>  $helper,
                    'valeur'    =>  $valeur,
                    'colonne'   =>  array(
                        array('label'   =>  'title', 'width'    => '10%'),
                        array('label'   => 'content', 'width'    => '80%')
                    ),
                    'actions'   =>  array(
                        array(
                            'type'  => 'show',
                            'color' => 'primary',
                            'url'   => '/cockpit/pages/show/:id',
                            'icon'  => 'fa-eye'
                        ),
                        array(
                            'type'  => 'edit',
                            'color' => 'info',
                            'url'   => '/cockpit/pages/edit/:id',
                            'icon'  => 'fa-pencil'
                        ),
                        array(
                            'type'  => 'delete',
                            'color' => 'danger',
                            'url'   => '/cockpit/pages/delete/:id',
                            'icon'  => 'fa-trash-o'
                        )
                    )
                );
                $helper = $this->helper($conf);
                $html = preg_replace('/'.$v[0].'/', $helper, $html);*/
            }
        }

        $matchesVar = array();
        preg_match_all("/{{ *([^}{ ]*) *}}/", $html, $matchesVar, PREG_SET_ORDER);

        if (!empty($matchesVar)) {
            foreach ($matchesVar as $v) {
                $model = $v[1];
                if (strpos($model, '.') !== false) {
                    $a = explode('.', $model, 2);
                    if (isset($params[$a[0]])) {
                        $obj = $params[$a[0]];
                        $key = $a[1];
                        $replace = isset($obj->$key) ? $obj->$key : '';
                    } else {
                        $replace = '';
                    }
                } else if (isset($params[$model])) {
                    $replace = $params[$model];
                } else {
                    $replace = '';
                }
                $html = str_replace($v[0], $replace, $html);
            }
        }

        return $html;
    }

    # Affiche les choses comme suivant le widget voulu
    # Array (
    #     'helper'      => 'table' | 'title',
    #     'valeur'      => array | string,
    #     'colonnes'    => array(array('label' => 'text, 'width' => '20%')),
    #     'actions'     => array(
    #            array('type' => 'edit', 'coulor' => 'primary', url => '/cockpit/pages/show/1', icon => 'fa-eye-o')
    #            array('type' => 'edit', )
    #            array('type' => 'delete')
    #      )
    #)
    public function helper($conf)
    {
        $html = '';
        switch ($conf['helper']) {
            case 'table':
                $html .= '<table class="table table-hover table-stripped">';
                $html .= '<thead>';
                $html .= '<tr>';
                foreach ($conf['colonne'] as $v_colonne) {
                    $html .= '<th width="'.$v_colonne['width'].'">'.ucfirst($v_colonne['label']).'</th>';
                }
                if (!empty($conf['actions'])) {
                    $html .= '<th width="10%">Actions</th>';
                }
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach ($conf['valeur'] as $v) {
                    $html .= '<tr>';
                    foreach ($conf['colonne'] as $v_colonne) {
                        $html .= '<td>'.$v->$v_colonne['label'].'</td>';
                    }
                    if (!empty($conf['actions'])) {
                        $html .= '<td>';
                        foreach ($conf['actions'] as $k_action => $v_action) {
                            $html .= '<a href="'.str_replace(':id', $v->id, $v_action['url']).'" class="btn btn-'.$v_action['color'].' btn-xs"><i class="fa '.$v_action['icon'].'"></i></a>';
                        }
                        $html .= '</td>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
                break;
            case 'title':
                $html .= '<h1 class="page-header">';
                if (!empty($conf['valeur'])) {
                    $html .= $conf['valeur'];
                }
                $html .= '</h1>';
                break;
            case 'articles_list':
                foreach ($conf['valeur'] as $k => $article) {
                    $html .= '<div class="row">';
                    $html .= '<div class="col-lg-3">';
                    $html .= '<h2>'.$article->title.'</h2>';
                    $html .= '</div>';
                    $html .= '<div class="col-lg-9">';
                    $html .= '<p>'.$article->content.'</p>';
                    $html .= '<p align="right"><a href="/articles/show/'.$article->id.'">Lire plus &rarr;</a></p>';
                    $html .= '</div>';
                    $html .= '</div>';
                    if ($k+1 != count($conf['valeur'])) {
                        $html .= '<hr />';
                    }
                }
                break;
        }

        return $html;
    }
}
