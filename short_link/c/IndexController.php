<?php

use Yison\HashId;
use Endroid\QrCode\QrCode;

class IndexController extends Controller
{
    const ERROR_OK = 0;
    const ERROR_ILLEGAL_URL = 1;
    /**
     * @var Mysqli_Database
     */
    protected $database;

    public function __construct()
    {
		error_reporting(E_ALL); 
		ini_set("display_errors", 1); 
        global $configs;
        $this->database = Mysqli_Database::getIntance($configs['db']['mysqli']);
    }

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

        $this->database->prepare("SELECT url FROM short_link WHERE `id` = ?;");
        $this->database->execute($id);
        $result = $this->database->results();
        if (empty($result[0]['url'])) {
            header('http/1.1 404');
            return;
        }

        $url = $result[0]['url'];
        header('http/1.1 302');
        header('location: ' . $url);
    }

    public function createShort()
    {
        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        if (!$url) {
            return $this->ajax([], self::ERROR_ILLEGAL_URL);
        }

        $urlMd5 = md5($url);
        $this->database->prepare("SELECT id FROM short_link WHERE `url_hash` = ?;");
        $this->database->execute($urlMd5);
        $result = $this->database->results();
        if (!empty($result[0]['id'])) {
            return $this->ajax([
                'hash_id' => (new HashId())->encode($result[0]['id']),
            ]);
        }

        $id = $this->database
            ->prepare("INSERT INTO `short_link` (url,url_hash, created_at) VALUES (?,?,?);")
            ->execute($url, md5($url), time())
            ->insert_id();

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
        $urlHashId = $segments[1];
        $httpProtocal = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $url = $httpProtocal . $_SERVER['HTTP_HOST'] . '/' . $urlHashId;

        $qrCode = new QrCode($url);
        header('Content-Type: ' . $qrCode->getContentType());
        echo $qrCode->writeString();
    }

    protected function ajax($data, $error = self::ERROR_OK, $message = '')
    {
        header('content-type:application/json;charset=utf8');
        $jsonData = [
            'error' => $error,
            'message' => $message,
            'data' => $data,
        ];
        echo json_encode($jsonData);
    }


}