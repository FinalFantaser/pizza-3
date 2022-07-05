<?php
    namespace App\ReadRepository\User;

    use Illuminate\Support\Facades\DB;
    use App\Models\User;
    use App\Models\Role;

    class AdminReadRepository{
        public function getMethods(){
            return User::join('role_user', ['users.id' => 'role_user.user_id']);
        } //findAll
    }