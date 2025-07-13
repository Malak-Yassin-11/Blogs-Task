<?php

namespace App\Modules\User\Requests;

use PDO;
class UpdateUserRequest extends CreateUserRequest {

    protected static function shouldValidatePassword(): bool {
        
        return false; 
    }
}
