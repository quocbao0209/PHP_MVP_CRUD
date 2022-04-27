<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    class ApiStudentController extends Controller { 

        public function __construct(){
            $this->studentModel = $this->model('Student');
        }

        public function api(){
            $method = $_SERVER['REQUEST_METHOD'];
            $response = array();
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            switch($method) {
                case 'GET':
                    if($id) {
                        $response = $this->getStudent($id);
                    } else {
                        $response = $this->getAllStudent();
                    }
                    break;
                case 'POST' :
                    $response = $this->createStudentFromRequest();
                    break;
                case 'PUT' :
                    $id = isset($_GET['id'])? $_GET['id'] : '';
                    if($id){
                        $response = $this->updateStudentFromRequest($id);

                    }
                    break;
                case 'DELETE':
                    $id = isset($_GET['id']) ? $_GET['id'] : '';
                    if($id){
                        $response = $this->deleteStudent($id);
                    }
                    break;
                default:
                    $response = $this->notFoundResponse();
                    break;
            }
            header($response['status_code_header']);
            if($response['body']) {
                echo $response['body'];
            }  
        }

        //Get all students
        private function getAllStudent() {
            $result = $this->studentModel->getAllStudents();
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }

        // get student by id
        private function getStudent($id) {
            $result = $this->studentModel->getStudentById($id);
            if (!$result) {
                return $this->notFoundResponse();
            }

            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }

        // validate input student
        private function validateStudent($input){
            if (!isset($input['code'])) {
                return false;
            }
            if (!isset($input['name'])) {
                return false;
            }
            if (!isset($input['birthday'])) {
                return false;
            }
            if (!isset($input['gender'])) {
                return false;
            }
            if (!isset($input['address'])) {
                return false;
            }
            if(!isset($input['number_phone'])) {
                return false;
            }
            return true;
        }

        // create student
        private function createStudentFromRequest(){
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateStudent($input)) {
                return $this->unprocessableEntityResponse();
            }
            $execute = $this->studentModel->createStudent($input);
            if ($execute) {
                $response['status_code_header'] = 'HTTP/1.1 201 Created';
                $response['body'] = null;
            } else {
                $response['status_code_header'] = 'HTTP/1.1 404 Cant Created';
                $response['body'] = null;
                
            }
            return $response;
        }


        // update student
        private function updateStudentFromRequest($id){
            $result = $this->studentModel->getStudentById($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateStudent($input)) {
                return $this->unprocessableEntityResponse();
            }
            $execute = $this->studentModel->updateStudent($input);
            if ($execute) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = null;
            } else {
                $response['status_code_header'] = 'HTTP/1.1 404 Cant Updated';
                $response['body'] = null;
            }
            return $response;
        }

        // delete student
        private function deleteStudent($id){
            $result = $this->studentModel->getStudentById($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $execute = $this->studentModel->deleteStudent($id);
            if ($execute) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = null;
            } else {
                $response['status_code_header'] = 'HTTP/1.1 404 Cant Delete';
                $response['body'] = null;
            }
            return $response;
        }

        // un process
        private function unprocessableEntityResponse(){
            $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
            $response['body'] = json_encode([
                'error' => 'Invalid input'
            ]);
            return $response;
        }

        // not found response
        private function notFoundResponse(){
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = null;
            return $response;
        }
    }   