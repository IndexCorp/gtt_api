<?php

class  Query {
        
        public $dbConn;
        public function __construct(){
            $db = new DbConnect;
            $this->dbConn = $db->connect();

        }


        public function getStudentDipResults($student_id){
            $stmt = $this->dbConn->prepare("SELECT `courses`.`id` AS course_id, `courses`.`course_name` AS course_name,`courses`.`course_abrev` AS course_abrev ,`courses`.`descs` AS descs ,`courses`.`avatar` AS avatar ,`courses`.`img_preview` AS img_preview  ,`courses`.`date_created` AS date_created, `diploma_result`.`module_1` AS module_1, `diploma_result`.`module_2` AS module_2, `diploma_result`.`module_3` AS module_3, `diploma_result`.`final` AS final, `diploma_result`.`final_grade` AS grade  FROM `diploma_result` INNER JOIN `courses` ON `courses`.`id` = `diploma_result`.`course_id` WHERE `diploma_result`.`student_id` = $student_id ");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $results;

            
        }
        


        public function getStudentResults($student_id){
            $stmt = $this->dbConn->prepare("SELECT `courses`.`id` AS course_id, `courses`.`course_name` AS course_name,`courses`.`course_abrev` AS course_abrev ,`courses`.`descs` AS descs ,`courses`.`avatar` AS avatar ,`courses`.`img_preview` AS img_preview  ,`courses`.`date_created` AS date_created, `result`.`module` AS score, `result`.`module_g` AS grade  FROM `result` INNER JOIN `courses` ON `courses`.`id` = `result`.`course_id` WHERE `result`.`student_id` = $student_id ");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $results;

            
        }
        

        public function getStudentCourses($student_id){
            $stmt = $this->dbConn->prepare("SELECT `courses`.`id` AS course_id, `courses`.`course_name` AS course_name,`courses`.`course_abrev` AS course_abrev ,`courses`.`descs` AS descs ,`courses`.`avatar` AS avatar ,`courses`.`img_preview` AS img_preview  ,`courses`.`date_created` AS date_created  FROM `courses` INNER JOIN `student_courses` ON `courses`.`id` = `student_courses`.`course_id` WHERE `student_courses`.student_id = $student_id AND publish = 1 ");
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $courses;
        }
        
        public function get_audios($course_id){
            $stmt = $this->dbConn->prepare("SELECT  `course_content`.`id`, `course_content`.`title`, `course_content`.`course_id` , `course_content`.`audio_link` , `course_content`.`link`, `course_content`.`descs` , `course_content`.`duration`, `course_content`.`viewed`, `course_content`.`date_created`, `courses`.`avatar` , `courses`.`img_preview`   FROM `course_content`  Inner Join  `courses` on `course_content`.`course_id` = `courses`.`id`  WHERE `course_content`.`course_id` = $course_id AND `course_content`.`type`= 'Audio' AND deleted != 1 ");
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $courses;
        }

        
        
        public function get_chats($student_id){
            $stmt = $this->dbConn->prepare("SELECT  `chats`.`id`, `chats`.`student_id`, `chats`.`admin_id` , `chats`.`title` , `chats`.`message`, `chats`.`reply` , `chats`.`status`, `chats`.`date_replied`, `chats`.`date_created`, `user`.`profileimage`  , `user`.`surname`  , `user`.`firstname`  , `user`.`id`   FROM `chats` Inner Join  `user` on `chats`.`student_id` = `user`.`id` WHERE `chats`.`student_id` = $student_id  ");
           
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $courses;
        }

        
        public function get_videos($course_id){
            $stmt = $this->dbConn->prepare("SELECT  `course_content`.`id`, `course_content`.`title`, `course_content`.`course_id` , `course_content`.`audio_link` , `course_content`.`link`, `course_content`.`descs` , `course_content`.`duration`, `course_content`.`viewed`, `course_content`.`date_created`, `courses`.`avatar`  , `courses`.`img_preview`   FROM `course_content` Inner Join  `courses` on `course_content`.`course_id` = `courses`.`id` WHERE `course_content`.`course_id` = $course_id AND `course_content`.`type`= 'Video' AND deleted != 1 ");
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $courses;
        }

        public function get_course_details($course_id){
            $stmt = $this->dbConn->prepare("SELECT  id, course_name, course_abrev , descs , avatar , img_preview  , date_created  FROM `courses` WHERE id = $course_id AND publish = 1 ");
            $stmt->execute();
            $courses = $stmt->fetch(PDO::FETCH_OBJ);
            return $courses;
        }
        



    
    public function get_multi($table, $fields = array(), $sort, $order)
    {
        $columns = '';
        $i       = 1;
        
        foreach($fields as $name => $value){
            $columns .= "`{$name}` = :{$name}";
             if($i < count($fields)){
                $columns .= ' AND ';
            }
            $i++;
        }
        $sql = "SELECT * FROM {$table}  WHERE {$columns} ORDER BY $sort $order";
        if($stmt = $this->dbConn->prepare($sql))
        {
            foreach($fields as $key => $value)
            {
                $stmt->bindValue(':'.$key, $value);
            } 
              $stmt->execute();
        $single = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return $single; 
    }


        
        public function register($firstname, $lastname, $email, $password, $phone){
            $firstnames = $firstname;
            $lastnames = $lastname;
            $emails = $email;
            $passwords = $password;
             $phone = $phone;
           

            $stmt = $this->dbConn->prepare("INSERT INTO user  (firstname, surname, email, password, tel ) VALUES (:firstname, :lastname, :email, :passwords, :tel) ");

            $stmt->bindParam(':firstname', $firstnames);
            $stmt->bindParam(':lastname', $lastnames);
            $stmt->bindParam(':email', $emails);
            $stmt->bindParam(':passwords', $passwords);
             $stmt->bindParam(':tel', $phone);
           
            $stmt->execute();
             //$result = $this->pdo->lastInsertId();
                //$stmt = null;
                if($stmt){ 
                    return true;
                }else{
                    return false;
                }
               
        }

        
        
        public function getAllCourses(){
            $stmt = $this->dbConn->prepare("SELECT * FROM `courses` ORDER BY `id` DESC");
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $courses;
           
        }


        /*
        public function saveChats($from, $msg, $to){
            return true;

        }

        
        public function getAllTeachers(){
            $stmt = $this->dbConn->prepare("SELECT * FROM `teachers` ORDER BY `id` ");
            $stmt->execute();
            $teachers = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $teachers;
           
        }

        public function getSubjectByTeacher($teacher_id){
            $stmt = $this->dbConn->prepare("SELECT * FROM `subjects` INNER JOIN `teachers_subject` ON `subject`.`id` = `teachers_subject`.`subject_id`  WHERE `teacher_id` = :id ORDER BY `subject`.`name` ASC");
            $stmt->execute(array(':id'=> $teacher_id));
            $teacher_id = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $teacher_id;    
        }

        public function count_ratings($teacher_id){
            $stmt = $this->dbConn->prepare("SELECT count(*) FROM `ratings` WHERE teacher_id = $teacher_id ");
            $stmt->execute();
            $rating_count = $stmt->fetchColumn(); 
            return $rating_count;
        }

        public function sum_ratings($teacher_id){
            $stmt = $this->dbConn->prepare("SELECT sum(rating) FROM `ratings` WHERE teacher_id = $teacher_id ");
            $stmt->execute();
            $rating_sum = $stmt->fetchColumn(); 
            return $rating_sum;
        }

       
        
        public function addChat($user_from, $user_to, $user_msg){
                $user_froms = $user_from;
                $user_tos = $user_to;
                $user_msgs = $user_msg;

               $stmt = $this->dbConn->prepare("INSERT INTO chat_chats  (user_from, user_to, msg) VALUES (:user_from, :user_to, :user_msg) ");
               $stmt->bindParam(':user_from', $user_froms);
               $stmt->bindParam(':user_to', $user_tos);
               $stmt->bindParam(':user_msg', $user_msgs);
                $stmt->execute();
                //$result = $this->pdo->lastInsertId();
                //$stmt = null;
                if($stmt){ 
                    return true;
                }else{
                    return false;
                }
               
                
    
        }

        public function addSubject($name, $class){
            $sname = $name;
            $classes = $class;
          

           $stmt = $this->dbConn->prepare("INSERT INTO chat_subjects  (sname, class) VALUES (:sname, :class) ");
           $stmt->bindParam(':sname', $sname);
           $stmt->bindParam(':class', $classes);
           $stmt->execute();
            //$result = $this->pdo->lastInsertId();
            //$stmt = null;
            if($stmt){ 
                return true;
            }else{
                return false;
            }         
            

        }
        function online(){
            global $con;
            global $app;
            $cur_user = (int)$app->user['id'];
            
            $newtimestamp = strtotime(date("Y-m-d H:i:s").' - 7 MINUTE');
            $_date_time = date("Y-m-d H:i:s", $newtimestamp);
            
            $now_date_time = date("Y-m-d H:i:s");
        
            //query("UPDATE chat_users SET status = 0 WHERE lastOnline <= '$_date_time'", false);
            //query("UPDATE chat_users SET status = 1, lastOnline = '$now_date_time' WHERE id = $cur_user", false);
        }

        public function addChatUser($firstname, $lastname, $email, $password, $push_id, $photo, $type, $city, $country, $phone, $school, $education, $career_experience, $bio, $other_interests, $status, $lastOnline){
            $firstnames = $firstname;
            $lastnames = $lastname;
            $emails = $email;
            $passwords = MD5($password);
            $push_ids = $push_id;
            $photos = $photo;
            $types = $type;
            $citys = $city;
            $countrys = $country;
            $phones = $phone;
            $schools = $school;
            $educations = $education;
            $career_experiences = $career_experience;
            $bios = $bio;
            $other_interest = $other_interests;
            $statu = $status;
            $lastOnlines = $lastOnline;
          

            $stmt = $this->dbConn->prepare("INSERT INTO chat_users  (firstname, lastname, email, password, push_id, photo, type, city, country, phone, school, education, career_experience, bio, other_interests, status, lastOnline) VALUES (:firstname, :lastname, :email, :password, :push_id, :photo, :type, :city, :country, :phone, :school, :education, :career_experience, :bio, :other_interests, :status, :lastOnline) ");

            $stmt->bindParam(':firstname', $firstnames);
            $stmt->bindParam(':lastname', $lastnames);
            $stmt->bindParam(':email', $emails);
            $stmt->bindParam(':password', $passwords);
            $stmt->bindParam(':push_id', $push_ids);
            $stmt->bindParam(':photo', $photos);
            $stmt->bindParam(':type', $types);
            $stmt->bindParam(':city', $citys);
            $stmt->bindParam(':country', $countrys);
            $stmt->bindParam(':phone', $phones);
            $stmt->bindParam(':school', $schools);
            $stmt->bindParam(':education', $educations);
            $stmt->bindParam(':career_experience', $career_experiences);
            $stmt->bindParam(':bio', $bios);
            $stmt->bindParam(':other_interests', $other_interest);
            $stmt->bindParam(':status', $statu);
            $stmt->bindParam(':lastOnline', $lastOnlines);

            $stmt->execute();
             //$result = $this->pdo->lastInsertId();
                //$stmt = null;
                if($stmt){ 
                    return true;
                }else{
                    return false;
                }
               
        }
        */

     
}