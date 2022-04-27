<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    class ApiClassController extends Controller {

        public function __construct()
        {
            $this->classModel = $this->model('Class');
        }

        private function getAllClass(){
            $result = $this->classModel->getAllClass();
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }

        private function getClass($id){
            $result = $this->teacherModel->getClassById($id);
            if(!$result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_decode($result);
            return $response;
        }

        private function validateClass($input){
            if(!isset($input['class_code'])){
                return false;
            }

            if(!isset($input['name_class'])){
                return false;
            }
            return false;
        }

        private function createClassFromRequest(){
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateClass($input)){
                return $this->unprocessableEntityResponse();
            }
            $execute = $this->classModel->createClass($input);
            if($execute){
                $response['status_code_header'] = 'HTTP/1.1 201 Created';
                $response['body'] = null;
            } else {
                $response['status_code_header'] = 'HTTP/1.1 2001 cant Created';
                $response['body'] = null;
            }
            return $response;
        }

        private function updateClassFromRequest(){
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateClass($input)){
                return $this->unprocessableEntityResponse();
            }
            $execute = $this->classModel->createClass($input);
            if($execute){
                $response['status_code_header'] = 'HTTP/1.1 201 Created';
                $response['body'] = null;
            } else {
                $response['status_code_header'] = 'HTTP/1.1 2001 cant Created';
                $response['body'] = null;
            }
            return $response;
        }

        private function deleteClassFromRequest(){
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateClass($input)){
                return $this->unprocessableEntityResponse();
            }
            $execute = $this->classModel->createClass($input);
            if($execute){
                $response['status_code_header'] = 'HTTP/1.1 201 Created';
                $response['body'] = null;
            } else {
                $response['status_code_header'] = 'HTTP/1.1 2001 cant Created';
                $response['body'] = null;
            }
            return $response;
        }

        private function unprocessableEntityResponse()
        {
            $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
            $response['body'] = json_encode([
                'error' => 'Invalid input'
            ]);
            return $response;
        }


        private function notFoundResponse()
        {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = null;
            return $response;
        }

        public function api(){
            $method = $_SERVER['REQUEST_METHOD'];
            $response = array();
            $id = isset($_GET['id']) ? $_GET ['id'] : '';
            switch($method) {
                case 'GET':
                    if($id){
                        $response = $this->getClass($id);
                    } else {
                        $response = $this->getAllClass();
                    }
                    break;
                case 'POST':
                    $response = $this->createClassFromRequest();
                    break;
                case 'PUT':
                    $id = isset($_GET['id']) ? $_GET['id'] : '';
                    if ($id) {
                        $response = $this->updateClassFromRequest($id);
                    }
                    break;
                case 'DELETE':
                    $id = isset($_GET['id']) ? $_GET['id'] : '';
                    if ($id) {
                        $response = $this->deleteClassFromRequest($id);
                    }
                    break;
                default:
                    $response = $this->notFoundResponse();
                    break;
            }
        }
    }

?>