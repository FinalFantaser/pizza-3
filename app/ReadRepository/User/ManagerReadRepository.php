<?php
    namespace App\ReadRepository\User;

    use Illuminate\Support\Facades\DB;

    use App\Models\User;
    use App\Models\Shop\City;

    class ManagerReadRepository{
        public function findAll(){ //Загрузить полный список менеджеров
            return DB::table('users')
                ->join('managers', 'users.id', '=', 'managers.user_id')
                ->join('cities', 'managers.city_id', '=', 'cities.id')
                ->select('users.id as user_id', 'users.name as user', 'cities.id as city_id', 'cities.name as city');
        } //findAll

        public function  findByCity(City $city){ //Загрузить менеджеров по городу
            return DB::table('users')
                ->join('managers', 'users.id', '=', 'managers.user_id')
                ->join('cities', 'managers.city_id', '=', $city->id)
                ->select('users.name');
        } //findByCity
    }