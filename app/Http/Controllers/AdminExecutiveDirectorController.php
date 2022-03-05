<?php

namespace App\Http\Controllers;

use App\Models\ExecutiveDirector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminExecutiveDirectorController extends Controller
{
    public function index(){
        $data = ExecutiveDirector::findOrFail(1);
        return view('backend.executive_director',compact('data'));
    }


    public function titleUpdate(Request $request){
        $this->updateWithValidation($request, false, 'title');
        return back();
    }

    public function speechUpdate(Request $request){
        $this->updateWithValidation($request, false, 'speech');
        return back();
    }

    public function imageUpdate(Request $request){
        $this->updateWithValidation($request, true, 'image');
        return back();
    }


    private function updateWithValidation(Request $request, $isFile, $field): void
    {
        $request->validate([
            $field => 'nullable'
        ]);

        $value = $request->$field;

        if($isFile == true){
            $request->validate([
                $field => 'required|mimes:png,jpeg,jpg,webp|max:1024'
            ]);
            if($request->hasFile($field)){
                $file = $request->file($field);
                $name = $file->hashName();
                Storage::putFileAs(
                    'public', $request->file($field),$name
                );

                $value = config('app.url').'/storage/'.$name;

            }
            
        }

        $settings = ExecutiveDirector::findOrFail(1);
        $settings->$field = $value;
        $settings->save();
    }
}