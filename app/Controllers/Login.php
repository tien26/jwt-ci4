<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class Login extends ResourceController
{
    protected $format = 'json';
    public function __construct()
    {
        $this->model_user = new UserModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        helper(['form']);

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = $this->request->getJSON();

        $user = $this->model_user->getUser($data->email);

        if ($user) {
            $verify = password_verify($data->password, $user['password']);
            if (!$verify) {
                return $this->fail("Wrong Password");
            } else {
                $key = getenv('TOKEN_SECRET');
                $payload = [
                    'iat' => 1356999524,
                    'nbf' => 1357000000,
                    'uid' => $user['id'],
                    'email' => $user['email']
                ];
                $jwt = JWT::encode($payload, $key, 'HS256');

                return $this->respond([
                    'status' => true,
                    'token' => $jwt
                ], 200);
            }
        } else {
            return $this->failNotFound("Email Not Found");
        }
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
