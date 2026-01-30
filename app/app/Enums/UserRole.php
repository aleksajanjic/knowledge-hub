<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MODERATOR = 'aoderator';
    case MEMBER = 'aember';
}
