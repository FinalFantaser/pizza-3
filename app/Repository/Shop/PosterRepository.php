<?php
    namespace App\Repository\Shop;

    use Illuminate\Support\Facades\DB;

    use App\Models\Shop\City;
    use App\Models\Shop\Poster;

    class PosterRepository{
        
        public function create(string $name, string $description, bool $enabled, $city = null){
            $poster = Poster::create(['name' => $name, 'description' => $description, 'enabled' => $enabled]);

            //Привязка к городам
            $this->attachToCity($poster, $city);

            //Загрузка картинки
            $this->updateImage($poster);

            return $poster;
        } //create

        public function update(Poster $poster, string $name, string $description, bool $enabled, $city = null){
            $poster->update(['name' => $name, 'description' => $description, 'enabled' => $enabled]);

            //Привязка к городам
            $this->clearAllCities($poster);
            $this->attachToCity($poster, $city);

            //Загрузка картинки
            $this->updateImage($poster);

            return $poster;
        } //update

        public function updateImage(Poster $poster){
            if(request()->hasFile('posterImage')){
                $poster->clearMediaCollection('posters');
                $poster->addMediaFromRequest('posterImage')->toMediaCollection('posters');
            }
        } //updateImage

        public function enable(Poster $poster){
            $poster->update(['enabled' => 1]);
        } //enable

        public function disable(Poster $poster){
            $poster->update(['enabled' => 0]);
        } //disable

        public function remove(Poster $poster): void{
            //Удаление картинки
            $poster->clearMediaCollection('posters');

            //Отвязка от городов
            $this->clearAllCities($poster);

            $poster->delete();
        } //remove

        public function attachToCity(Poster $poster, null|int|string $city): void{
            if(is_int($city))
                DB::table('posters_cities')->updateOrInsert(['poster_id' => $poster->id, 'city_id' => $city]);
            elseif(is_string($city)){
                $ids = json_decode($city);
            
                if(is_int($ids)){
                    DB::table('posters_cities')->updateOrInsert(['poster_id' => $poster->id, 'city_id' => $ids]);
                    return;
                }

                if(count($ids) < 1)
                    return;
                
                //Создание массива
                $data = array_map(function($value) use ($poster) {
                    return [
                        'poster_id' => $poster->id,
                        'city_id' => $value
                    ];
                }, $ids);
                
                foreach($data as $item)
                    DB::table('posters_cities')->updateOrInsert($item);
            }

            // DB::table('posters_cities')->updateOrInsert(['poster_id' => $poster->id, 'city_id' => $city->id]);
        } //attachToCity

        public function detachFromCity(Poster $poster, null|int|string $city): void{
            if(is_int($city))
                DB::table('posters_cities')->where(['poster_id' => $poster->id, 'city_id' => $city])->delete();
            elseif(is_string($city)){
                $ids = json_decode($city);

                if(is_int($ids)){
                    DB::table('posters_cities')->where(['poster_id' => $poster->id, 'city_id' => $ids])->delete();
                    return;
                }

                DB::table('posters_cities')->where('poster_id', $poster->id)->whereIn('city_id', $ids)->delete();
            }

            // DB::table('posters_cities')->where(['poster_id' => $poster->id, 'city_id' => $city->id])->delete();
        } //detachFromCity

        public function clearAllCities(Poster $poster){
            DB::table('posters_cities')->where(['poster_id' => $poster->id])->delete();
        } //clearAllCities
    }