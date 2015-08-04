<?php
    trait Asserts{
        protected $pdo = null;
        public function __construct(){
            $this->pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);//$GLOBALS are from file phpunit.xml
        }

        public function assertFileExistsInDB($ids, $message = ''){
            $ids = (array) $ids;
            if(empty($ids)) return;

            $sql = 'SELECT id from `files` where id in ('.implode(', ', $ids).')';
            $sth = $this->pdo->prepare($sql);
            $sth->execute();
            $rows = $sth->fetchAll();

            /*foreach($rows as $row){
                $id = $row['id'];
                $ids = array_diff($ids, [$id]);
            }*/

            //if(!empty($ids)) {
            if(count($ids) == count($rows)){
                $message = !empty($message)?$message:'Files '.implode(', ', $ids) . ' not found in db';
                $this->fail($message);
            }

            return true;
        }

        public function assertFileColumnsInDB($ids, $owner_id, $owner_type, $section, $message = ''){
            $ids = (array) $ids;
            if(empty($ids)) return;

            $sql = 'SELECT id, owner_type, owner_id, section from `files` where id in ('.implode(', ', $ids).')';
            $sth = $this->pdo->prepare($sql);
            $sth->execute();
            $rows = $sth->fetchAll();
            //print_r($rows);
            foreach($rows as $row){
                $r_id = $row['id'];
                $r_owner_id = $row['owner_id'];
                $r_owner_type = $row['owner_type'];
                $r_section = $row['section'];
                if($r_owner_id == $owner_id && $r_owner_type == $owner_type && $r_section == $section)
                    $ids = array_diff($ids, [$r_id]);
            }

            if(!empty($ids)) {
                $message = !empty($message)?$message:'Files '.implode(', ', $ids) . ' not as expected';
                $this->fail($message);
            }

            return true;
        }
    }
?>