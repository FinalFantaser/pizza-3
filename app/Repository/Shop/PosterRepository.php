<?php
    namespace App\Repository\Shop;

    use Illuminate\Support\Facades\DB;

    use App\Models\Shop\City;
    use App\Models\Shop\Poster;

    class PosterRepository{
        
        public function create(string $name, string $description, bool $enabled){
            return Poster::create(['name' => $name, 'description' => $description, 'enabled' => $enabled]);
        } //create

        public function update(Poster $poster, string $name, string $description, bool $enabled){
            $poster->update(['name' => $name, 'description' => $description, 'enabled' => $enabled]);
            return $poster;
        } //update

        public function deleteImage(Poster $poster){
            if ($poster->getFirstMedia('posterImages'))
                $poster->getFirstMedia('posterImages')->delete();
        } //deleteImage

        public function updateImage(Poster $poster){
            $this->deleteImage($poster);
            $poster->addMediaFromRequest('posterImage')->toMediaCollection('posterImages');
        } //updateImage

        public function enable(Poster $poster){
            $poster->update(['enabled' => 1]);
        } //enable

        public function disable(Poster $poster){
            $poster->update(['enabled' => 0]);
        } //disable

        public function remove(Poster $poster): void{
            $this->deleteImage($poster);
            $poster->delete();
        } //remove

        public function attachToCity(Poster $poster, City $city): void{
            DB::table('posters_cities')->updateOrInsert(['poster_id' => $poster->id, 'city_id' => $city->id]);
        } //attachToCity

        public function detachFromCity(Poster $poster, City $city): void{
            DB::table('posters_cities')->where(['poster_id' => $poster->id, 'city_id' => $city->id])->delete();
        } //detachFromCity
    }