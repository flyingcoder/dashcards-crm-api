<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class UpdateImageUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-image-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update models whose thumbnail used version 6 of spatie/medialibrary';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $string
     * @param $start
     * @param $end
     * @return false|string
     */
    protected function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $affecteds = User::withTrashed()->where('image_url', 'like', '%/conversions/thumb.jpg')->get();
        foreach ($affecteds as $key => $user) {
            $folder_id = $this->get_string_between($user->image_url, 'storage/','/conversions');
            $path = storage_path('app/public/'.$folder_id.'/conversions');

            if (file_exists($path)) {
                $files = array_values(array_filter(scandir($path), function($file) use ($path) { 
                    return !is_dir($path . '/' . $file);
                }));
                if ($files[0]) {
                    $user->image_url = str_replace('thumb.jpg', trim($files[0]), $user->image_url);
                    $user->save();
                }
            }   
        }
        echo "All Done";
    }
}
