<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Models\File;
use App\Trait\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ApiController extends Controller
{
    use response;
    //
    public function storeFile(Request $r)
    {
        //validation
        $validate = Validator::make($r->all(), [
            'name' => 'required|mimes:jpeg,png,jpg,gif,pdf',
        ]);
        if ($validate->fails()) {
            return   $this->responseApi(false, $validate->errors(), null);
        }
        //store file in attachment directory
          $file_name = time() . '' . $r->file('name')->getClientOriginalName();
          $r->file('name')->storeAs('Files', $file_name, 'upload');
          $file_extension = $r->file('name')->getClientOriginalExtension();
          $file_type= $r->file('name')->getClientMimeType();
          $file_size = round($r->file('name')->getSize() / 1024 / 1024, 2);

        //add image in database
        $data = File::create([
          'name'=>$file_name,
          'type'=> $file_type ,
            'extension'=> $file_extension,
            'size'=> $file_size,
        ]);
        if ($data) {
            return   $this->responseApi(true, [], new DataResource($data));
        }
        //if data didnt store
        return   $this->responseApi(false, 'failed to save data try again', null);
    }

    public function getFiles()
    {
        $data = File::paginate(20);
        if (!empty($data) && count($data) > 0) {
            $paginationLinks = [
                    'first' => $data->url(1),
                    'last' => $data->url($data->lastPage()),
                    'prev' => $data->previousPageUrl(),
                    'next' => $data->nextPageUrl(),
            ];
            return   $this->responseApi(true,[], DataResource::collection($data),$paginationLinks);
        }
        return   $this->responseApi(false, 'not found data');
    }


    public function searchFiles($value)
    {
        $data = File::where('id', $value)->orWhere('name', 'like', "%$value%")->paginate(20);

        if (!empty($data) && count($data) > 0) {
            $paginationLinks = [
                    'first' => $data->url(1),
                    'last' => $data->url($data->lastPage()),
                    'prev' => $data->previousPageUrl(),
                    'next' => $data->nextPageUrl(),
            ];
            return $this->responseApi(true,[],DataResource::collection($data), $paginationLinks );
        }

        return $this->responseApi(false,'File not found');
    }

    public function deleteFile($id)
    {
        $data = File::where('id', $id)->first();
        if (!empty($data)) {
            Storage::disk('upload')->delete('Files/' . $data->name);
            $data->delete();
            return $this->responseApi(true);
        }
        return $this->responseApi(false,'File not found');
    }
}
