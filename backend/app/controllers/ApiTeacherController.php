<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    class ApiTeacherController extends Controller {


        public function __construct(){
            $this->teacherModel = $this->model('Student');
        }

        private function getAllTeacher(){
           $result = $this ->teacherModel->getAllTeacher();
           $response['status_code_header'] = 'HTTP/1.1 200 OK';
           $response['body'] = json_encode($result);
           return $response;
        }
        

        private function getTeacher($id){
            $result = $this->teacherModel->getTeacherById($id);
            if(!$result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_decode($result);
            return $response;
        }

        private function validateTeacher($input){
            if(!isset($input['teacher_code'])){
                return false;
            }

            if(!isset($input['teacher_name'])){
                return false;
            }

            if(!isset($input['gender'])){
                return false;
            }

            if(!isset($input['birthday'])){
                return false;
            }

            if(!isset($input['address'])){
                return false;
            }

            if(!isset($input['number_phone'])) {
                return false;
            }
            
            return false;
        }

        private function createTeacherFromRequest(){
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateTeacher($input)){
                return $this->unprocessableEntityResponse();
            }
            $execute = $this->teacherModel->createTeacher($input);
            if($execute){
                $response['status_code_header'] = 'HTTP/1.1 201 Created';
                $response['body'] = null;
            } else {
                $response['status_code_header'] = 'HTTP/1.1 2001 cant Created';
                $response['body'] = null;
            }
            return $response;
        }

        private function updateTeacherFromRequest(){
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateTeacher($input)){
                return $this->unprocessableEntityResponse();
            }
            $execute = $this->teacherModel->createTeacher($input);
            if($execute){
                $response['status_code_header'] = 'HTTP/1.1 201 Created';
                $response['body'] = null;
            } else {
                $response['status_code_header'] = 'HTTP/1.1 2001 cant Created';
                $response['body'] = null;
            }
            return $response;
        }

        private function deleteTeacherFromRequest(){
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateTeacher($input)){
                return $this->unprocessableEntityResponse();
            }
            $execute = $this->teacherModel->createTeacher($input);
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
                        $response = $this->getTeacher($id);
                    } else {
                        $response = $this->getAllTeacher();
                    }
                    break;
                case 'POST':
                    $response = $this->createTeacherFromRequest();
                    break;
                case 'PUT':
                    $id = isset($_GET['id']) ? $_GET['id'] : '';
                    if ($id) {
                        $response = $this->updateTeacherFromRequest($id);
                    }
                    break;
                case 'DELETE':
                    $id = isset($_GET['id']) ? $_GET['id'] : '';
                    if ($id) {
                        $response = $this->deleteTeacherFromRequest($id);
                    }
                    break;
                default:
                    $response = $this->notFoundResponse();
                    break;
            }
        }
    }
?>