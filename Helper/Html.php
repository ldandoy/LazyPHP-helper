<?php

namespace Helper;

use Core\Router;
use Helper\DatetimeUtils;

class Html
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
        
        $p['id'] = isset($params['id']) ? ' id="'.$params['id'].'"' : '';

        $p['class'] = isset($params['class']) ? ' class="'.$params['class'].'"' : '';

        return array_merge($params, $p);
    }

    /**
     * Generate title tag
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function title($params = array())
    {
        $params = self::parseParams($params);
        $content = isset($params['content']) ? $params['content'] : '';

        $html = '<h1 class="page-header">'.$content.'</h1>';

        return $html;
    }

    /**
     * Generate title tag
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function articleslist($params = array())
    {
        $params = self::parseParams($params);
        
        $html = "";
        foreach ($params['articles'] as $k => $article) {
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-12">';
            $html .= '<h2>'.$article->title.'</h2>';
            $html .= '</div>';
            $html .= '<div class="col-lg-12">';
            $html .= '<p>'.$article->content.'</p>';
            $html .= '<p align="right"><a href="/article/'.$article->id.'">Lire plus &rarr;</a></p>';
            $html .= '</div>';
            $html .= '</div>';
            if ($k+1 != count($params['articles'])) {
                $html .= '<hr />';
            }
        }

        return $html;
    }

    /**
     * Generate link tag
     *
     * @param mixed $params
     *      content
     *      icon
     *      url
     *      newWindow 0 | 1
     *      confirmation
     *
     * @return string
     */
    public static function link($params = array())
    {
        $params = self::parseParams($params);

        $url = isset($params['url']) ? Router::url($params['url']) : '';

        $target = isset($params['newWindow']) && $params['newWindow'] == '1' ? ' target="_blank"' : '';

        $content = isset($params['content']) ? $params['content'] : '';

        $icon = isset($params['icon']) ? $params['icon'] : '';
        if ($icon != '') {
            $icon = '<i class="fa fa-'.$icon.'"></i>';
            if ($content != '') {
                $icon .= '&nbsp;';
            }
        }
        $content = $icon.$content;

        $html = '<a href="'.$url.'"'.$target.$params['id'].$params['class'].'>'.$content.'</a>';

        return $html;
    }

    /**
     * Generate image tag
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function image($params = array())
    {
        $params = self::parseParams($params);

        $src = isset($params['src']) ? ltrim(Router::url($params['src']), '/') : '';

        $file = PUBLIC_DIR.'/'.$src;
        if (file_exists($file)) {
            $src = '/'.$src;
        } else {
            $file = PUBLIC_DIR.'/assets/images/'.$src;
            if (file_exists($file)) {
                $src = '/assets/images/'.$src;
            } else {
                $src = '';
            }
        }

        $html = '<img src="'.$src.'"'.$params['id'].$params['class'].' />';

        return $html;
    }

    /**
     * Generate table
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function table($params = array())
    {
        $id = isset($params['id']) ? ' id="'.$params['id'].'"' : '';

        $class = isset($params['class']) ? $params['class'] : '';
        $class = ' class="'.$class.' table table-hover table-stripped table-sm datatable"';

        $dataset = isset($params['dataset']) ?
            $params['dataset'] :
            array(
                'columns' => array(),
                'data' => array()
            );

        $columns = $dataset['columns'];
        $data = $dataset['data'];

        $html =
            '<table '.$id.$class.'>';

        $html .=
                '<thead>'.
                    '<tr>';
        foreach ($columns as $column) {
            if (!isset($column['visible']) || $column['visible']) {
                $html .=
                        '<th>'.$column['title'].'</th>';
            }
        }
                    '</tr>'.
                '</thead>';

        $html .=
                '<tbody>';
        foreach ($data as $row) {
            $html .=
                    '<tr>';
            foreach ($columns as $column) {
                if (!isset($column['visible']) || $column['visible']) {
                    $dataOrder = '';
                    $dataSearch = '';
                    if ($column['name']=='col1' && $row[$column['name']] > 2) {
                        $dataSearch = ' data-search="!!"';
                    }
                    switch ($column['type']) {
                        case 'datetime':
                            $value = $row[$column['name']];
                            $ts = DatetimeUtils::stringToTimestamp($value);
                            $dataOrder = ' data-order="'.$ts.'"';
                            if (isset($column['format'])) {
                                $value = DatetimeUtils::format($value, $column['format']);
                            } else {
                                $value = DatetimeUtils::format($value, DatetimeUtils::FORMAT_DATETIME);
                            }
                            break;
                        case 'int':
                        case 'float':
                        case 'string':
                        default:
                            $value = $row[$column['name']];
                            break;
                    }

                    $html .=
                        '<td'.$dataOrder.$dataSearch.'>'.$value.'</td>';
                }
            }
                    '</tr>';
        }

        $html .=
            '</table>';

        return $html;
    }
}
