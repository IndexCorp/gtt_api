<?php
   
  require "vendor/autoload.php";
    use \Firebase\JWT\JWT;
    //use \Firebase\JWT;
    require_once('Rest.php');
    require_once('Query.php');
    
    require_once('dbConnect.php');
  

    class  Api extends Rest {
        
        public $dbConn;
        
        public function __construct(){
            parent::__construct();
            
            $db = new DbConnect;
            $this->dbConn = $db->connect();

            

        }
        
        public function getAudios(){
            $token = $this->param['token'];
            $course_id = $this->validateParameter('course_id', $this->param['course_id'], STRING);
           
           
         
            $query = new Query;
            try {
                $token = $token;
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $student_id = $payload->userId;
                if(!empty($student_id)){
                    
                    $courses = $query->get_audios($course_id);
                    $data = ['audios' => $courses];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        
        public function getChats(){
                 $token = $this->param['token'];
                 $course_id = $this->validateParameter('course_id', $this->param['course_id'], STRING);
                
                
              
                 $query = new Query;
                 try {
                     $token = $token;
                     $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                     $student_id = $payload->userId;
                     if(!empty($student_id)){
                         
                         $chats = $query->get_chats($student_id);
                         $data = ['chats' => $chats];
                         $this->returnResponse(SUCCESS_RESPONSE, $data);           
                         
                     }
                    
                 } catch (Exception $e){
                     $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
                 }
             }
     

             public function getVideos(){
                $token = $this->param['token'];
                $course_id = $this->validateParameter('course_id', $this->param['course_id'], STRING);
               
               
             
                $query = new Query;
                try {
                    $token = $token;
                    $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                    $student_id = $payload->userId;
                    if(!empty($student_id)){
                        
                        $courses = $query->get_videos($course_id);
                        $data = ['videos' => $courses];
                        $this->returnResponse(SUCCESS_RESPONSE, $data);           
                        
                    }
                   
                } catch (Exception $e){
                    $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
                }
            }
    
         

        public function getCourseDetails() {
            $token = $this->param['token'];
            $course_id = $this->validateParameter('course_id', $this->param['course_id'], STRING);
           
           
         
            $query = new Query;
            try {
                $token = $token;
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $student_id = $payload->userId;
                if(!empty($student_id)){
                    
                    $courses = $query->get_course_details($course_id);
                    $data = ['course' => $courses];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

           
        public function getUserCourses() {
            $token = $this->param['token'];
           
         
            $query = new Query;
            try {
                $token = $token;
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $student_id = $payload->userId;
                if(!empty($student_id)){
                    
                    $courses = $query->getStudentCourses($student_id);
                    $data = ['courses' => $courses];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        

           
        public function getUserResults() {
            $token = $this->param['token'];
           
         
            $query = new Query;
            try {
                $token = $token;
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $student_id = $payload->userId;
                if(!empty($student_id)){
                    
                    $results = $query->getStudentResults($student_id);
                    $data = ['results' => $results];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        

           
        public function getUserDipResults() {
            $token = $this->param['token'];
           
         
            $query = new Query;
            try {
                $token = $token;
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $student_id = $payload->userId;
                if(!empty($student_id)){
                    
                    $results = $query->getStudentDipResults($student_id);
                    $data = ['results' => $results];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        


        public function generateToken(){
            $email = $this->validateParameter('email', $this->param['email'], STRING);
            $pass = $this->validateParameter('pass', $this->param['pass'], STRING);
            $password =  MD5($pass);
            try {
                $stmt = $this->dbConn->prepare("SELECT * FROM `user` WHERE email = :email AND password = :pass");
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":pass", $password);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_OBJ);

                if($user->type == "teacher"){
                    $payload = [
                        'iat' => time(),
                        'iss' => 'localhost',
                        'exp' => time() + (2592000),
                         'is_std' => 'no',
                         'is_teach' => 'yes',
                        'userId' => $user->id,
                        
        
                    ];

                    
                $token = JWT::encode($payload, SECRETE_KEY);
                $data = ['token' => $token];
                $this->returnResponse(SUCCESS_RESPONSE, $data);

                }elseif($user->type == "student") {
                    $payload = [
                        'iat' => time(),
                        'iss' => 'localhost',
                        'exp' => time() + (3600),
                         'is_std' => 'yes',
                         'is_teach' => 'no',
                        'userId' => $user->id,
                        
        
                    ];

                    
                $token = JWT::encode($payload, SECRETE_KEY);
                $data = ['token' => $token];
                $this->returnResponse(SUCCESS_RESPONSE, $data);
                }
                if(!$user){
                    $this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect.");
                }
                
                
           

            } catch (Exception $e) {
                $this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
            }
        }


        
        public function allCourses() {
            $query = new Query;
            try {
                $token = $this->getBearerToken();
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $from = $payload->userId;
                if(!empty($from)){
                    
                    $courses = $query->getAllCourses();
                    $data = ['courses' => $courses];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        /*public function generateTeachersToken(){
            $email = $this->validateParameter('email', $this->param['email'], STRING);
            $pass = $this->validateParameter('pass', $this->param['pass'], STRING);
             
            $stmt = $this->dbConn->prepare("SELECT * FROM `teachers` WHERE email = :email AND password = :pass");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":pass", $pass);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!is_array($user)){
                $this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect.");
            }
            $payload = [
                'iat' => time(),
                'iss' => 'localhost',
                'exp' => time() + (3600),
                 'is_std' => 'no',
                 'is_teach' => 'yes',
                'userId' => $user['id'],
               
    
            ];
            
            $token = JWT::encode($payload, SECRETE_KEY);
            $data = ['token' => $token];
            $this->returnResponse(SUCCESS_RESPONSE, $data);
        }*/

        public function register() {
            $firstname = $this->validateParameter('firstname', $this->param['firstname'], STRING, false);
            $lastname = $this->validateParameter('lastname', $this->param['lastname'], STRING, false);
            $email = $this->validateParameter('email', $this->param['email'], STRING, false);       
            $password =  $this->param['password'];
            $phone = $this->validateParameter('phone', $this->param['phone'], STRING, false);
          
                //$token = $this->getBearerToken();
                //$payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                //$from = $payload->userId;
                //echo $from;
                $query = new Query;
                if($query->register($firstname, $lastname, $email, MD5($password), $phone)){
                    $message = 'User Created Successfully';
                }else{
                    $message = 'Failed to Create User';
                }
                $this->returnResponse(SUCCESS_RESPONSE, $message);
           
        }

/*
        public function addChatUsers() {
            $firstname = $this->validateParameter('firstname', $this->param['firstname'], STRING, false);
            $lastname = $this->validateParameter('lastname', $this->param['lastname'], STRING, false);
            $email = $this->validateParameter('email', $this->param['email'], STRING, false);       
            $password = $this->validateParameter('password', $this->param['password'], STRING, false);
            $push_id = $this->validateParameter('push_id', $this->param['push_id'], STRING, false);
            $photo = $this->validateParameter('photo', $this->param['photo'], STRING, false);
            $type = $this->validateParameter('type', $this->param['type'], STRING, false);       
            $city = $this->validateParameter('city', $this->param['city'], STRING, false);
            $country = $this->validateParameter('country', $this->param['country'], STRING, false);
            $phone = $this->validateParameter('phone', $this->param['phone'], STRING, false);
            $school = $this->validateParameter('school', $this->param['school'], STRING, false);       
            $education = $this->validateParameter('education', $this->param['education'], STRING, false);
            $career_experience = $this->validateParameter('career_experience', $this->param['career_experience'], STRING, false);
            $bio = $this->validateParameter('bio', $this->param['bio'], STRING, false);
            $other_interests = $this->validateParameter('other_interests', $this->param['other_interests'], STRING, false);       
            $status = $this->validateParameter('status', $this->param['status'], STRING, false);
            $lastOnline = $this->validateParameter('lastOnline', $this->param['lastOnline'], STRING, false);

                //$token = $this->getBearerToken();
                //$payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                //$from = $payload->userId;
                //echo $from;
                $query = new Query;
                if($query->addChatUser($firstname, $lastname, $email, $password, $push_id, $photo, $type, $city, $country, $phone, $school, $education, $career_experience, $bio, $other_interests, $status, $lastOnline)){
                    $message = 'User Created Successfully';
                }else{
                    $message = 'Failed to Create User';
                }
                $this->returnResponse(SUCCESS_RESPONSE, $message);
           
        }

        public function addChat() {
            $user_from = $this->validateParameter('user_from', $this->param['user_from'], INTEGER, true);
            $user_to = $this->validateParameter('user_to', $this->param['user_to'], INTEGER, true);
            $user_msg = $this->validateParameter('user_msg', $this->param['user_msg'], STRING, true);
           
            try {
                $token = $this->getBearerToken();
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                //$from = $payload->userId;
               //print_r($payload);
                $query = new Query;
                if($query->addChat($user_from, $user_to, $user_msg)){
                    $message = 'Chat Created Successfully';
                }else{
                    $message = 'Failed to Create Chat';
                }
                $this->returnResponse(SUCCESS_RESPONSE, $message);
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        public function addSubject() {
            $name = $this->validateParameter('name', $this->param['name'], STRING, true);
            $class = $this->validateParameter('class', $this->param['class'], STRING, true);
           
            try {
                $token = $this->getBearerToken();
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $from = $payload->userId;
                //echo $from;
                $query = new Query;
                if($query->addSubject($name, $class)){
                    $message = 'Chat Created Successfully';
                }else{
                    $message = 'Failed to Create Chat';
                }
                $this->returnResponse(SUCCESS_RESPONSE, $message);
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        public function saveChat() {
            $user_from = $this->validateParameter('from', $this->param['from'], INTEGER, false);
            $user_to = $this->validateParameter('to', $this->param['to'], INTEGER, false);
            $user_msg = $this->validateParameter('msg', $this->param['msg'], STRING, false);

            try {
                $token = $this->getBearerToken();
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $from = $payload->userId;
                //echo $from;
                $query = new Query;
                if($query->addChat($user_from, $user_to, $user_msg)){
                    $message = 'Chat Created Successfully';
                }else{
                    $message = 'Failed to Create Chat';
                }
                $this->returnResponse(SUCCESS_RESPONSE, $message);
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        public function teachersList() {
            $query = new Query;
            try {
                $token = $this->getBearerToken();
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $from = $payload->userId;
                if(!empty($from)){
                    $teachers = $query->getAllTeachers();
                    $data = ['teachers' => $teachers];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        public function sumRatings() {
            $query = new Query;
            try {
                $token = $this->getBearerToken();
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $from = $payload->userId;
                $teacher_id =$this->param['teacher_id'];
                if(!empty($from)){
                    $sum = $query->sum_ratings($teacher_id);
                    $data = ['sum' => $sum];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        public function countRatings() {
            $query = new Query;
            try {
                $token = $this->getBearerToken();
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $from = $payload->userId;
                $teacher_id =$this->param['teacher_id'];
                if(!empty($from)){
                    $count = $query->count_ratings($teacher_id);
                    $data = ['count' => $count];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        public function subjectByTeacher() {
            $query = new Query;
            try {
                $token = $this->getBearerToken();
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
                $from = $payload->userId;
                $teacher_id =$this->param['teacher_id'];
                if(!empty($from)){
                    $subjects = $query->getSubjectByTeacher($teacher_id);
                    $data = ['subjects' => $subjects];
                    $this->returnResponse(SUCCESS_RESPONSE, $data);           
                    
                }
               
            } catch (Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        */
    }



?>