<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MandubSuggestion;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\ClientSuggestion;
use Validator;
use App\Http\Requests\SuggationRequest;
use File;

class AddSuggationController extends Controller
{
    public function AddClientSuggation(SuggationRequest $request)
    {
        try {
            $user = auth('clients')->user();
            // $user->update($request->except(['token']));
            //upload profile_image if exist

            $data = ClientSuggestion::create([
                'client_id' => $user->id,
                'description' => $request->description,

            ]);
            if ($request->hasFile('image')) {
                $destination = public_path() . '/images/clientSuggation';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->image->getClientOriginalName();
                $request->image->move($destination, $imageName);
                $data->update(['image' => $imageName]);
            }

            $this->initResponse('', $data, 200, 'data');

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    public function AddMandubSuggation(SuggationRequest $request)
    {
        try {
            $user = auth('mandubs')->user();
            //upload profile_image if exist
            $data = MandubSuggestion::create([
                'mandub_id' => $user->id,
                'suggation_type' => $request->suggation_type,
                'description' => $request->description,

            ]);
            if ($request->hasFile('image')) {
                $destination = public_path() . '/images/mandubSuggation';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->image->getClientOriginalName();
                $request->image->move($destination, $imageName);
                $data->update(['image' => $imageName]);
            }

            $this->initResponse('', $data, 200, 'data');

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);

    }
}
