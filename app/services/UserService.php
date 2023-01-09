<?php
    namespace Services;

    class UserService {
        const ADMIN = 'admin';
        const STUDENT = 'student';
        const TEACHER = 'teacher';
        const STAFF = 'staff';


        public static function getTypes(){
            return [
                self::ADMIN,
                self::STUDENT,
                self::TEACHER,
                self::STAFF
            ];
        }
    }