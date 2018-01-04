<?php

use Yison\HashId;
use Endroid\QrCode\QrCode;

class IndexController extends Controller
{
    public const ERROR_OK = 0;
    public const ERROR_ILLEGAL_URL = 1;

    public function index()
    {
        $this->display();
    }

    public function recover()
    {
        $segments = getUriSegments();
        $urlHashId = $segments[0];
        $hashId = new HashId();
        $id = $hashId->decode($urlHashId);
        $url = 'query url from db where id';
        header('http/1.1 302');
        header('location: ' . $url);
    }

    public function createShort()
    {
        $url = filter_input(INPUT_POST, FILTER_VALIDATE_URL, 'url');
        if (!$url) {
            return $this->ajax([], self::ERROR_ILLEGAL_URL);
        }

        $id = 'create db record';
        $hashId = new HashId();
        $urlHashId = $hashId->encode($id);
        $data = [
            'hash_id' => $urlHashId,
        ];
        $this->ajax($data);
    }

    public function qr()
    {
        $segments = getUriSegments();
        $urlHashId = $segments[0];
        $hashId = new HashId();
        $id = $hashId->decode($urlHashId);
        $url = 'db where id';

        $qrCode = new QrCode($url);

        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
    }

    protected function ajax($data, $error = self::ERROR_OK, $message = '')
    {
        header('application/json; charset=utf-8');
        $jsonData = [
            'error' => $error,
            'message' => $message,
            'data' => $data,
        ];
        echo json_encode($jsonData);
    }


}