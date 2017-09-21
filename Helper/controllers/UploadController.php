<?php

namespace Helper\controllers;

use app\controllers\FrontController;
use Core\AttachedFile;
use Core\Router;

class UploadController extends FrontController
{
    public function indexAction()
    {
        $res = array(
            'error' => false,
            'message' => ''
        );

        $post = $this->request->post;

        if (!empty($post)) {
            $file = new AttachedFile('', $post['upload_file'][0], $post['upload_type']);
            $hasError = false;
            $errorFile = $file->valid();
            if ($errorFile !== true) {
                $res['error'] = true;
                $res['message'] = $errorFile;
            } else {                
                $errorFile = $file->saveUploadedFile('tmp', 0, $post['upload_input_name']);

                if ($errorFile !== true) {
                    $res['error'] = true;
                    $res['message'] = $errorFile;
                } else {
                    $res['type'] = $post['upload_type'];
                    $res['inputId'] = $post['upload_input_id'];
                    $res['inputName'] = $post['upload_input_name'];
                    $res['url'] = $file->url;
                }
            }
        } else {
            $res['error'] = true;
            $res['message'] = 'Upload error';
        }

        echo json_encode($res);
        exit;
    }
}
