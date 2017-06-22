<?php

namespace Helper\controllers;

use app\controllers\FrontController;
use Core\Session;
use Core\Router;

class UploadController extends FrontController
{
    public function uploadAction()
    {
        $res = array(
            'error' => false,
            'message' => ''
        );

        $post = $this->request->post;

        if (!empty($post)) {
            $res['type'] = $post['upload_type'];
            $res['inputId'] = $post['upload_input_id'];
            $res['inputName'] = $post['upload_input_name'];
            $res['src'] = '???';
        } else {
            $res['error'] = true;
            $res['message'] = 'Upload error';
        }

        echo json_encode($res);
        exit;
    }
}
