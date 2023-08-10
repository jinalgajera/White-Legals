<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use DataTables, DB;
use Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    public $folder = 'user';
    public $route = 'users';
    
    public function index()
    {
        $route = $this->route;
        return view($this->folder.'/index', compact('route'));
    }

   
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view($this->folder.'/add', compact('roles'));
    }

    
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'roles' => 'required'
        ]);

        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => bcrypt('12345678'), 'phone_number' => $request->phone_number, 'status'=> 1]);
        $user->assignRole($request->input('roles'));
        return redirect($this->route)->with('success','User created successfully');
    }

    public function getUsersData(){      
        $users = User::get();

        return Datatables::of($users)
        ->addIndexColumn()
        ->addColumn('action', function($users){

            $editUrl = route('users.edit', $users->id);   
            $action = '';        
            if($users->status == 1){
                $type = 'inactive';  
                if (auth()->user()->can('user-edit')) {
                    $action .= '<a href="'.$editUrl.'" class="edit btn btn-warning btn-sm">Edit</a> '; 
                }
                if (auth()->user()->can('user-delete')) {
                    $action .= '<a href="" class="edit btn btn-danger btn-sm confirm-delete" href="javascript:void(0)" data-id="'.$users->id.'"">Delete</a> ';
                }
                if (auth()->user()->can('user-aciveinactive')) {
                    $action .= '<a href="" class="edit btn btn-success btn-sm activeStatus" href="javascript:void(0)" data-id="'.$users->id.'"" data-type="'.$type.'">Active</a>';
                }           
               
            } else {
                $type = 'active';
                $statusAction = url('userActiveInactiveStatus/'.$type.'/'.$users->id);
                   
                if (auth()->user()->can('user-edit')) {
                    $action .= '<a href="'.$editUrl.'" class="edit btn btn-warning btn-sm">Edit</a> ';
                }
                if (auth()->user()->can('user-delete')) {
                    $action .= '<a href="" class="edit btn btn-danger btn-sm confirm-delete" href="javascript:void(0)" data-id="'.$users->id.'"">Delete</a> ';
                }
                if (auth()->user()->can('user-aciveinactive')) {
                    $action .= '<a href="" class="edit btn btn-danger btn-sm activeStatus" href="javascript:void(0)" data-id="'.$users->id.'"" data-type="'.$type.'">Inactive</a>';  
                }             
            }
            return $action;
        })
        ->editColumn('status', function ($users) {
			if ($users->status == 1) {
				return "<div style='color: green;'>Active</div>";
			} else {
				return "<div style='color: red;'>Inactive</div>";
			}
		})
        ->rawColumns(['action', 'status'])
        ->make(true); 
    }
    
   
    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view($this->folder.'/edit', compact('user', 'roles', 'userRole'));
    }
  
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));

        return redirect($this->route)->with('success','User updated successfully');
    }
 
    public function destroy($id)
    {
        if(User::where('id', $id)->delete()){
            echo json_encode(true);
        }
        else{
            echo json_encode(false);
        }
    }

    public function userActiveInactiveStatus($type,$id)
    {        
        if ($type == 'active') {
            User::where('id', $id)->update(['status'=>'1']);           
        } else {
            User::where('id', $id)->update(['status'=>'0']);            
        }
        echo json_encode(true);
    }
}
