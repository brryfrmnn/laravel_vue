<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Blog;
use App\Http\Requests;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;

class BlogController extends Controller
{
    public function index()
    {
        
    }

    /*public function vueCrud(){
      return view('/vuejscrud/index');
    }*/

    /*public function index()
    {
        $items = Blog::latest()->paginate(6);
        $response = [
          'meta' => [
            'total' => $items->total(),
            'offset' => $items->perPage(),
            'current' => $items->currentPage(),
            'last' => $items->lastPage(),
            'from' => $items->firstItem(),
            'to' => $items->lastItem()
          ],
          'data' => $items->all()
        ];
        return response()->json($response);
    }*/

    public function store(Request $request)
    {
        $this->validate($request,[
          'title' => 'required',
          'description' => 'required',
        ]);
        $create = Blog::create($request->all());
        return response()->json($create);
    }

    public function update(Request $request, $id)
    {
      $this->validate($request,[
        'title' => 'required',
        'description' => 'required',
      ]);
      $edit = Blog::find($id)->update($request->all());
      return response()->json($edit);
    }
    
    public function destroy($id)
    {
        Blog::find($id)->delete();
        return response()->json(['done']);
    }
}
