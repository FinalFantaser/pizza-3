<?php
    namespace App\Repository\User;

    use Illuminate\Support\Facades\DB;
    use App\Models\User;
    use App\Models\Role;

    class AdminRepository{
        public function assign(User $user){
            $admin = Role::where('name', Role::ROLE_ADMIN)->first();
            DB::table('role_user')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'role_id' => $admin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        } //assign

        public function dismiss(User $user){
            //Если администратор остался в системе один, не позволять удалить его
            if( DB::table('role_user')->select()->count() < 2)
                return;

            $admin = Role::where('name', Role::ROLE_ADMIN)->first();
            DB::table('role_user')->where(['user_id' => $user->id, 'role_id' => $admin->id])->delete();
        } //dismiss
    }