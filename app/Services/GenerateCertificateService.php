<?php


namespace App\Services;

use Illuminate\Support\Facades\File;
use App\Models\Certificate;
use App\Models\User;
use App\Models\UserCertificate;
use Carbon\Carbon;
use Str;

class GenerateCertificateService
{

  private function hexToRgb(string $hex): array
    {
        // Remove the hash if it exists
        $hex = ltrim($hex, '#');

        // If shorthand (3 characters), convert to 6 characters
        if (strlen($hex) === 3) {
            $hex = str_repeat($hex[0], 2) . str_repeat($hex[1], 2) . str_repeat($hex[2], 2);
        }

        // Ensure the hex is valid
        if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
            throw new \InvalidArgumentException("Invalid HEX color code.");
        }

        // Convert to RGB values
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

  public function generateCertificate($certificate_id, $user_id) {

    $example = false;

    $certificate = Certificate::find($certificate_id);

    if($user_id == 0) {
      if($certificate->config['name_mention'] == 'full_name'){
        $name = 'John Doe';
      } else {
        $name = 'John';
      }
      $date = Carbon::now()->format('d F, Y');
      $example = true;
    } else {
      $user = User::find($user_id);

      if($certificate->config['name_mention'] == 'full_name'){
        $name = $user->first_name . ' ' . $user->last_name;
      } else {
        $name = $user->first_name;
      }

     
      $date = Carbon::now()->format('d F, Y');
      
    }

    // Path to the certificate template
    $templatePath = public_path($certificate->image);
    $templatePath = str_replace('public', 'assets', $templatePath);

    // Check the extension of the uploaded image
    $ext = strtolower(pathinfo($templatePath, PATHINFO_EXTENSION));

    // Load the image based on its extension
    switch ($ext) {
      case 'png':
        $image = imagecreatefrompng($templatePath);
        break;

      case 'jpeg':
      case 'jpg':
        $image = imagecreatefromjpeg($templatePath);
        break;

      default:
        abort(400, 'Unsupported image format. Only PNG, JPEG, and JPG are allowed.');
    }

    // If image loading failed, abort with an error
    if (!$image) {
      dd('Failed to process the template image.');
    }
    
    // Set the font file (ensure this file exists in your public/fonts directory)
    $fontPath = str_replace('public', 'assets', public_path('global/fonts/arial.ttf')); // Ensure the font exists in public/fonts
    if (!file_exists($fontPath)) {
      dd('Font file not found.');
    }

    // Name 
    $fontSize = $certificate->config['name_font_size'];
    $name_x = $certificate->config['coordinate_x_name']; 
    $name_y = $certificate->config['coordinate_y_name'] + $certificate->config['name_font_size']; 
    $name_font_color_hex = $this->hexToRgb($certificate->config['name_font_color']);
    $name_text_color = imagecolorallocate($image, $name_font_color_hex['r'], $name_font_color_hex['g'], $name_font_color_hex['b']);
    imagettftext($image, $fontSize, 0, $name_x, $name_y, $name_text_color, $fontPath, $name);

    // Date
    $fontSize = $certificate->config['date_font_size'];
    $date_x = $certificate->config['coordinate_x_date']; 
    $date_y = $certificate->config['coordinate_y_date'] + $certificate->config['date_font_size']; 
    $date_font_color_hex = $this->hexToRgb($certificate->config['date_font_color']);
    $date_text_color = imagecolorallocate($image, $date_font_color_hex['r'], $date_font_color_hex['g'], $date_font_color_hex['b']);
    imagettftext($image, $fontSize, 0, $date_x, $date_y, $date_text_color, $fontPath, $date);

    // Save the output as a new image
    $random_string = Str::random(20);
    $outputPath = 'assets/global/images/' .  $random_string . '.' . $ext;
    imagepng($image, $outputPath);

    // if example then delete the previous example

    if(isset($certificate->config['example_certificate'])) {
      $previous_example_path = public_path($certificate->config['example_certificate']);
      $previous_example_path = str_replace('public', 'assets', $previous_example_path);
      if (File::exists($previous_example_path) && $example == true) {
        File::delete($previous_example_path);
      }
    }
    

    // Return the generated image URL
    return str_replace('assets/', '', $outputPath);
  }
}
