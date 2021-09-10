<?php

namespace Ares\Modules\Core\API;

class User {

    private $request;
    private $response;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function details()
    {
        $userDetails = [];

        $userDetails['fullName'] = "Bhaskar Verma";
        $userDetails['email'] = "bhaskar@ares.com";
        $userDetails['profileImg'] = "https://ui-avatars.com/api/?name=Bhaskar Verma&size=128";

        $resp = json_encode($userDetails);
        $this->response->getBody()->write($resp);
        return $this->response;
    }

}