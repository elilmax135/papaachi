<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\File;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Clear uploaded media files
        $folderPath = public_path('media');
        File::cleanDirectory($folderPath);

        $folderPath = public_path('storage');
        File::cleanDirectory($folderPath);

        //Copy original CSS file from Duplicate CSS
        $sourcePath = public_path('assets/css/copy.css');
        $destinationPath = public_path('assets/css/style.css');

        try {
            File::copy($sourcePath, $destinationPath);
            $this->command->info('CSS file copied successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error copying CSS file: ' . $e->getMessage());
        }

        // Replace color value in CSS file
        $cssFilePath = public_path('assets/css/style.css');
        $cssContent = file_get_contents($cssFilePath);

        // Replace the placeholder with the retrieved color code
        $cssContent = str_replace('{{main}}', '#BD1701' , $cssContent);
        $cssContent = str_replace('{{second}}', '#ED523D' , $cssContent);

        // Save the updated CSS content
        file_put_contents($cssFilePath, $cssContent);

        // Save app settings contents
        Setting::create([
            'company_name' => 'Extreme Coders',
            'email' => 'info@extremecoders.us',
            'mobile' => '0772353119',
            'logo' => 'logo',
            'favicon' => 'favicon',
            'login_img' => 'login_path',
            'profile' => 'profile' ,
            'desc' =>'Software Development Company',
            'tags'=>'Jaffna,Software Company, Custom Laravel App',
            'solution' => 'Extreme Coders 🚀'
        ]);
    }
}
