<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HomeController
{
	/**
	 * Response as QRcode
	 */
	public static function index(Request $request, Response $response)
	{
		$data = new stdClass();
		$data->cnpj = "aaa";
		$data->userid = "1";
		$data->token = "12356";

		$qr = \PHPQRCode\QRcode::png(json_encode($data), "/tmp/qrcode.png", 'L', 10, 1, false, true);
		$imgContent = file_get_contents('/tmp/qrcode.png');
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($imgContent);
		$img = "<img src='$base64'>";
		return $response->getBody()->write($img);
	}
}
