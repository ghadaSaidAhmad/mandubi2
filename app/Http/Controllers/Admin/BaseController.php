<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

class BaseController extends Controller
{
    protected $model;

    protected $view;

    public function __construct()
    {
        View::share('searchTypes', $this->searchTypes);
    }

    public function index(Request $request)
    {

        $data = $this->model::orderBy('created_at', 'desc')->paginate(10);
        $data->appends(request()->input())->links();
        if ($request->ajax())
        {
            //$data->appends(request()->input())->links();
            $table = view("admin.$this->view.table", compact('data'))->render();

            $res = [
                'status' => true,
                'table' => $table
            ];
            return response($res);
        }
        $table = view("admin.$this->view.table", compact('data'))->render();

        return view("admin.$this->view.index", compact('table'));
    }

    public function search(Request $request)
    {

        $term =  $request->get('p');


        if ( array_key_exists($request['search_type'], $this->searchTypes)) {

            if ($request['search_type'] === 'id')

            {
                $data = $this->model::where($request['search_type'], $request['search'])->paginate(1);
            }
            else
            {
               $data = $this->model::where($request['search_type'], 'LIKE', "%" . $request['search'] ."%");

              $data=$data->paginate(10);

            }


        } else {
            $data = $this->model::paginate(10);
        }
       // return $data;
       // $data->appends(request()->input('page'))->links();


        $table = view("admin.$this->view.table", compact('data'))->render();

        $res = [
            'status' => true,
            'table' => $table
        ];
        return response($res);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->model::destroy($id);
            $res = [
                'status' => true,
                'title' => 'تم بنجاح',
                'message' => 'تم الحذف'
            ];
        } catch (\PDOException $exception) {
            switch ($exception->getCode()) {
                case '23000':
                    $res = [
                        'status' => false,
                        'title' => 'فشل',
                        'message' => 'لا يمكن الحذف لانه مستخدم'
                    ];
                    break;
                default:
                    $res = [
                        'status' => false,
                        'title' => 'فشل',
                        'message' => 'لا يمكن الحذف لسبب غير معروف'
                    ];
            }
        }

        return response($res);

    }

    public function edit($id)
    {
        $object = $this->model::find($id);
       // dd($object);
        $model = view("admin.$this->view.edit", compact('object'))->render();
        $res = [
            'status' => true,
            'model' => $model
        ];

        return response($res);
    }

}
