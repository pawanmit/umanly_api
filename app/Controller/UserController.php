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

    public function getUsersNearLocationWithinDistance() {
        $this->autoRender = false;
        $this->response->type('json');
        $params = array('longitude', 'latitude', 'distance');
        if ($this->checkForMissingParams($params)) {
            return;
        };
        $latitude = 37.78104350;
        $longitude = -122.39571030;
        $distance = 2;
        $sql = "SELECT user.id, user.first_name, user.last_name, location.latitude, location.longitude,  ( 3959 * acos( cos( radians(" . $latitude .") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(" . $longitude .") )
                                    + sin( radians(" . $latitude .") ) * sin( radians( latitude ) ) ) ) AS distance
                                     FROM location , user WHERE user.id = location.user_id HAVING distance < ". $distance ." ORDER BY distance LIMIT 0 , 20;";
        //print_r($sql);
        $result = $this->User->query($sql);
        $output = $this->normalizeLocationResult($result);
        //print_r($output);
        return json_encode( $output );

    }

    function normalizeLocationResult($result) {
        $users = array();
        $count = 0;
        foreach($result as $row) {
            $users[$count] = $row['user'];
            $users[$count]['location'] = $row['location'];
            $users[$count]['distance'] = $row['0']['distance'];
            $count++;
        }
        return $users;
    }

    public function updateUserLocation() {
        $this->log("updating users location");
        $this->autoRender = false;
        $this->response->type('json');

        $fields = array('longitude', 'latitude');
        if ($this->checkForMissingData($fields)) {
            return;
        };

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

    private function checkForMissingParams($params) {
        foreach($params as $param) {
            $this->log("Checking for " . $param);
            if ( !isset($this->request->query[$param] )  ) {
                $this->response->statusCode(400);
                $error = "Bad input data. Missing " . $param . " information";
                $response = array("error" => $error);
                $this->response->body(json_encode($response));
                return $this->response;
            }
        }
    }

    private function checkForMissingData($fields) {
        foreach($fields as $field) {
            $this->log("Checking for " . $field);
            if ( !isset($this->request->data[$field] )  ) {
                $this->response->statusCode(400);
                $error = "Bad input data. Missing " . $field . " information";
                $response = array("error" => $error);
                $this->response->body(json_encode($response));
                return $this->response;
            }
        }
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