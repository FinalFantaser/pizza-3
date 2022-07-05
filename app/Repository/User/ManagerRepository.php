<?php
    namespace App\Repository\User;

    use Illuminate\Support\Facades\DB;

    use App\Models\User;
    use App\Models\Shop\City;

    class ManagerRepository{
        
        public function assign(User $user, City $city){ //Назначить пользователя региональным менеджером
            //Пользователь может быть назначен только на один город (изменить, если потребуется)
            DB::table('managers')->updateOrInsert(['user_id' => $user->id], ['city_id' => $city->id]);
        } //assign

        public function dismiss(User $user){ //Снять с пользователя полномочия регионального менеджера
            DB::table('managers')->where('user_id', $user->id)->delete();
        } //dismiss
    }