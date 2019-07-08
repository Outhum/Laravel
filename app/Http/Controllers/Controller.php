<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs; 
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Request;
use FFMpeg;
use Storage;
use File;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function gifUpload()
    {

        return view('upload');

    }
    ////////////////////////////////////////////////////////////////////////First you need to insatll ffmpeg package using composer ///////////////////////
    //////////////////////////////////////////////////////////////////sudo apt-get update , sudo apt-get install ffmpeg//////////////////////////
    public function gifUploadPost(){
         request()->validate([

            'image' => 'required|image|mimes:gif,svg,webp|max:2048', 

        ]);
       /* request()->validate([
             'image' => 'dimensions:min_width=1920,min_height=1080', ///checking the size of gif return true is file size is valid
        ]);*/
        $imageName   = time().'.'.request()->image->getClientOriginalExtension();
        $arr         = explode(".", $imageName);
        $last        = $arr[0];
 
        request()->image->move(public_path('images'), $imageName); 
        $videoFile   = "images/".$imageName;
        $output      = "images/".$last.'.mp4';
        $cmd         = "ffmpeg -i $videoFile -ss 00:00:0.0 -t 10 -an $output";

        exec($cmd,$output,$exit_status); //Convert the gif to video

        $ffmpeg_path = '/usr/bin/ffmpeg'; // ffmpeg image converion package installed here through composer using command sudo apt-get update then sudo apt-get install ffmpeg
        $vid = '/opt/lampp/htdocs/test/public/images/'.$last.'.mp4'; //full path where my converted video is stored
        if ($exit_status === 0) { // returns 0 if the shell scripts executes correctly
            if(file_exists($vid)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $vid);
            finfo_close($finfo);
                if (preg_match('/video\/*/', $mime_type)) {
                        $command = $ffmpeg_path . ' -i ' . $vid . ' -vstats 2>&1';
                        $output = shell_exec($command);
                        $regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/";
                        if (preg_match($regex_sizes, $output, $regs)) {
                            $codec = $regs [1] ? $regs [1] : null;
                            $width = $regs [3] ? $regs [3] : null;
                            $height = $regs [4] ? $regs [4] : null;
                        }
                        $regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
                        if (preg_match($regex_duration, $output, $regs)) {
                            $hours = $regs [1] ? $regs [1] : null;
                            $mins = $regs [2] ? $regs [2] : null;
                            $secs = $regs [3] ? $regs [3] : null;
                            $ms = $regs [4] ? $regs [4] : null;
                        }
                        //Below are the details required from the converted gif.
                        $arr = array('codec' => $codec,
                            'width' => $width,
                            'height' => $height,
                            'hours' => $hours,
                            'mins' => $mins,
                            'secs' => $secs,
                            'ms' => $ms
                        );
                        File::delete($videoFile);
                        return back()

                        ->with('success','You have successfully upload gif.Your Converted video length is '.$hours.':'.$mins.':'.$secs);

                        //->with('image',$imageName); 

                } else {
                
                    dd('error','File is not a video.');
                }
        }else{
           
                dd('error','File is not a video.');
            }
    }else{
        dd('There is some problem in conversion of gif to video');
    }

    

       /* return back()

            ->with('success','You have successfully upload gif.')

            ->with('image',$imageName);*/
 
    }

    public function imagetovideo(){ 
        request()->validate([
             'image2' => 'required|image|mimes:jpg,png',
        ]);
    	$imageName = time().'.'.request()->image2->getClientOriginalExtension();
        //dd($imageName);
        $arr = explode(".", $imageName);
        $last = $arr[0];
        request()->image2->move(public_path('images'), $imageName);
        $videoFile = "images/".$imageName;
        $output    = "images/".$last.'.mp4';
        $cmd = "ffmpeg -r 1/10 -i $videoFile -c:v libx264 -vf fps=25 -pix_fmt yuv420p $output";

        exec($cmd,$output,$exit_status); //Execute the commands for conversion

        if ($exit_status === 0) {        // IF done delete the image 
         File::delete($videoFile);
        }
        return back()

            ->with('success','You have successfully upload image.');

            //->with('image',$imageName);
    	
    }
    public function videoupload(){
        
        request()->validate([
            'video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
         ]);
        $file = Request::file('video');
        if(!empty($file->getClientOriginalExtension())){
            $ffprobe = FFMpeg\FFProbe::create();
            $duration = $ffprobe
                ->format($file->getRealPath())
                ->get('duration');
            if(round($duration) > 60 || round($duration) < 1){
                return back()
                ->with('success','Video length allowed between 1 to 60 seconds.');
             }
            else{
                $filename = $file->getClientOriginalName();
                $path = public_path().'/videos/';
                $file->move($path, $filename);
                return back()
                ->with('success','You have successfully upload video.');
            }
        }else{
                return false;
            }
        

    }
    	
}
