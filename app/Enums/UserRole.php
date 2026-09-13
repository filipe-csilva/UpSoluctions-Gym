<?php

namespace App\Enums;

enum UserRole : string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case TEACHER = 'teacher';
    case STUDENT = 'student';
    case FINANCIAL = 'financial';
}
