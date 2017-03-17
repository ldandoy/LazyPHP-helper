<?php

namespace Helper;

use System\Router;
use Helper\Datetime;

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
     * Generate link tag
     *
     * @param mixed $params
     *
     * @return string
     */
    public static function link($params = array())
    {
        $params = self::parseParams($params);

        $url = isset($params['url']) ? Router::url($params['url']) : '';

        $target = isset($params['new_window']) && $params['new_window'] == '1' ? ' target="_blank"' : '';

        $content = isset($params['content']) ? $params['content'] : '';

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
        $class = ' class="'.$class.' table table-hover table-stripped"';

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
            $html .=
                        '<th>'.$column['title'].'</th>';
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
                    switch ($column['type']) {
                        case 'datetime':
                            $value = $row[$column['name']];
                            if (isset($column['format'])) {
                                $value = Datetime::format($value, $column['format']);
                            } else {
                                $value = Datetime::format($value, FORMAT_DATETIME);
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
                        '<td>'.$value.'</td>';
                }
            }
                    '</tr>';
        }

        $html .=
            '</table>';

        return $html;
    }
}
