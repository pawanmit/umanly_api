<?php

class UserController extends AppController {

    public $uses = array( 'User', 'Location');

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
        $this->autoRender = false;
        $this->response->type('json');

        if ( !isset($this->request->data['longitude'] ) || !isset($this->request->data['latitude']) ) {
            $this->response->statusCode(400);
            $error = "Bad input data. Missing longitude or latitude information";
            $response = array("error" => $error);
            $this->response->body(json_encode($response));
            return;
        }

        $user_id = $this->request->params['id'];
        try {
            $location = $this->Location->find('all', array('conditions' => array('user_id' =>  $user_id )));
            if ( isset ($location) && (count($location) > 0) ) {
                //print_r($location); die;
                $this->request->data["user_id"] = $location[0]["Location"]["user_id"];
                $this->request->data["id"] = $location[0]["Location"]["id"];
                $this->log("Updating user location for user id " . $user_id);
                $this->Location->create();
                $this->Location->save($this->request->data);
            } else {
                $this->request->data["user_id"] = $user_id;
                $this->Location->create();
                $this->Location->save($this->request->data);
            }
        } catch (Exception $e) {
            $this->handleModelException($e);
            return;
        }
        $response = array ("id" => $this->Location->id);
        $this->response->body(json_encode($response));
    }

    public function createOrUpdateUser() {
        $this->autoRender = false;
        $this->response->type('json');
        $user = null;
        if ( array_key_exists('email', $this->request->data ) && strlen($this->request->data['email']) > 0 ) {
            $email = $this->request->data['email'];
            $this->log("finding user with email "  . $email );
            $user = $this->User->find('all', array('conditions' => array('email' =>  $email )));
        }
        try {
            if (!$user || (count($user) < 1) ) {
                $this->log("Creating users");
                $this->User->create();
                $this->User->save($this->request->data);
            } else {
                $this->log("Updating users");
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