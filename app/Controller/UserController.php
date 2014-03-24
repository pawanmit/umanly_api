<?php

class UserController extends AppController {

    public $uses = array( 'User');

    public function getAllUsers() {
        $this->log("getting all users");
        try {
            $response = $this->User->find('all');
        } catch (Exception $e) {
           $response = $e->getMessage();
           $this->response->statusCode(500);
        }
        $this->autoRender = false;
        $this->response->type('json');
        return json_encode( $response );
    }

    public function updateUserLocation() {
        $this->log("updating users location");
        $this->response->type('json');
    }

    public function createOrUpdateUser() {
        $this->autoRender = false;
        $this->response->type('json');
        $user = null;
        if ( array_key_exists('email', $this->request->data )
            && strlen($this->request->data['email']) > 0 ) {
            $email = $this->request->data['email'];
            $this->log("finding user with email "  . $email );
            $user = $this->User->find('all', array('conditions' => array('email' =>  $email )));
        }
        try {
            if (!$user) {
                $this->log("creating users");
                $this->User->create();
                $this->User->save($this->request->data);
            } else {
                $user_id = $user[0]['User']['id'];
                $this->User->id = $user_id;
                $this->User->save($this->request->data);
            }
        } catch ( Exception $e) {
            $this->handleModelException($e);
        }
        $response = array ("id" => $this->User->id);
        $this->response->body(json_encode($response));
    }


    private function handleModelException($e) {
        $errorMessage = $e->getMessage();
        $response = array("error" => $errorMessage);
        $this->response->statusCode(500);
        $this->response->body(json_encode($response));
    }
}