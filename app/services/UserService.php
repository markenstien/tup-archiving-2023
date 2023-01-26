<?php
    namespace Services;

    class UserService {
        const ADMIN = 'ADMINISTRATOR';
        const STUDENT = 'student';
        const TEACHER = 'teacher';
        const STAFF = 'staff';
        const COMMON = 'common-user';


        public static function getTypes(){
            return [
                self::ADMIN,
                self::STUDENT,
                self::TEACHER,
                self::STAFF,
                self::COMMON
            ];
        }
    }