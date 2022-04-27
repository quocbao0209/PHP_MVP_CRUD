<?php 
    class Student extends Controller{
        public function __construct(){
            $this->studentModel = $this->model('Student');
        }

        public function index(){
            $students = $this->studentModel->getStudents();

            $data = [
                'title' => 'Home page',
                'students' => $students
            ];

            $this->view('students/index', $data);
        }

        //create student
        public function create(){
            $data = [
                'code' => '',
                'name' => '',
                'birthday' => '',
                'gender' => '',
                'address' => '',
                'message' => array(
                    'codeError'=>'',
                    'nameError'=>'',
                    'birthdayError'=>'',
                    'genderError'=>'',
                    'addressError'=>'',
                    'duplicate' => ''
                )
            ];

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
                $code = $_POST['code'];
                $name = $_POST['name'];
                $birthday = $_POST['birthday'];
                $gender = $_POST['gender'];
                $address = $_POST['address'];
                $data = [
                    'code'=> trim($code),
                    'name'=> trim($name),
                    'birthday'=> trim($birthday),
                    'gender'=> trim($gender),
                    'address'=> trim($address),
                    'message' => array(
                        'codeError'=>'',
                        'nameError'=>'',
                        'birthdayError'=>'',
                        'genderError'=>'',
                        'addressError'=>'',
                        'duplicate' => ''
                    )
                ];
            }

            if (empty($data['code'])) {
                $data['message']['codeError'] = 'The code of a student cannot be empty';
            }
            if (empty($data['name'])) {
                $data['message']['nameError'] = 'The name of a student cannot be empty';
            }
            if (empty($data['birthday'])) {
                $data['message']['birhtdayError'] = 'The birthday of a student cannot be empty';
            }
            if (isset($data['gender']) && $data['gender'] == '') {
                $data['message']['genderError'] = 'The gender of a student cannot be empty';
            }
            if (empty($data['address'])) {
                $data['message']['addressError'] = 'The address of a student cannot be empty';
            }

            $status_when_submit = STATUS_TRUE; //status when submit
            //check pass all data;
            foreach ($data['message'] as $key => $value) {
                // $default_status = !empty($value) ? 0 : 1;
                if (!empty($value)) { 
                    $status_when_submit = STATUS_FALSE;
                    break; // break loop when one value has message
                }
            }

            //submit data
            if ($status_when_submit == STATUS_TRUE) {
                if ($this->studentModel->createStudent($data)) {
                    header("Location: " . URLROOT . "/students");
                } else {
                    $data['message']['duplicate'] = 'Duplicate Student! Please try again!';
                    $this->view('students/create', $data);
                }
            } else {
                $this->view('students/create', $data);
            }
            $this->view('students/create', $data);
        }

        // update student
        public function update($id){
            $student_selected = $this->studentModel->getStudentById($id);
            $data = [
                'students'=>$student_selected,
                'code'=> '',
                'name'=> '',
                'birthday'=> '',
                'gender'=> '',
                'address'=> '',
                'message' => array(
                    'codeError'=>'',
                    'nameError'=>'',
                    'birthdayError'=>'',
                    'genderError'=>'',
                    'addressError'=>'',
                    'duplicate' => ''
                )
            ];

                // check request
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
                $new_code = $_POST['code'];
                $new_name = $_POST['name']; 
                $new_birthday = $_POST['birthday'];//day default dd-mm-yyyy
                $new_birthday = !empty($_POST['birthday']) ? date("Y-m-d H:i:s", strtotime($new_birthday)) : '';
                $new_gender = $_POST['gender'];
                $new_address = $_POST['address'];
                $data = [
                    'student'=>$student_selected,
                    'code'=> trim($new_code),
                    'name'=> trim($new_name),
                    'birthday'=> trim($new_birthday),
                    'gender'=> trim($new_gender),
                    'address'=> trim($new_address),
                    'message' => array(
                        'codeError'=>'',
                        'nameError'=>'',
                        'birthdayError'=>'',
                        'genderError'=>'',
                        'addressError'=>'',
                        'duplicate' => ''
                    )
                ]  ;

                //validation data
            if (empty($data['code'])) {
                $data['message']['codeError'] = 'The code of a student cannot be empty';
            }
            if (empty($data['name'])) {
                $data['message']['nameError'] = 'The name of a student cannot be empty';
            }
            if (empty($data['birthday'])) {
                $data['message']['birthdayError'] = 'The birthday of a student cannot be empty';
            }
            if (isset($data['gender']) && $data['gender'] == '') {
                $data['message']['genderError'] = 'The gender of a student cannot be empty';
            }
            if (empty($data['address'])) {
                $data['message']['addressError'] = 'The address of a student cannot be empty';
            }
            
            $status_when_submit = STATUS_TRUE; //status when submit
            //check pass all data;
            foreach ($data['message'] as $key => $value) {
                // $default_status = !empty($value) ? 0 : 1;
                if (!empty($value)) { 
                    $status_when_submit = STATUS_FALSE;
                    break; // break loop when one value has message
                }
            }

            //submit data
            if ($status_when_submit == STATUS_TRUE) {
                if ($this->studentModel->updateStudent($data)) {
                    header("Location: " . URLROOT . "/students");
                } else {
                    $data['message']['duplicate'] = 'Duplicate Student! Please try again!';
                    $this->view('students/update', $data);
                }

                } else {
                    $this->view('students/update', $data);
                }
            }
            $this->view('students/update', $data);
        }

        // delete student
        public function delete($id)
        {
            $this->studentModel->getStudentById($id);
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
                if ($this->studentModel->deleteStudent($id)) {
                    header('Location: ' . URLROOT . '/students' );
                } else {
                    die('Something went wrong, please try again');
                }
            }
        }
}
?>