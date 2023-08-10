<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use DataTables;

class ClientController extends Controller
{
    public $folder = 'client';
    public $route = 'clients';

    public function index()
    {
        $route = $this->route;
        return view($this->folder.'/index', compact('route'));
    }
  
    public function create()
    {
        return view($this->folder.'/add');
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'city' => 'required',
            'notes' => 'required'
        ]);

        Client::create(['user_id' => auth()->user()->id, 'name'=> $request->name, 'email' =>  $request->email, 'address' => $request->address, 'city' => $request->city, 'notes' => $request->notes]);

        return redirect($this->route)->with('success','Client created successfully');
    }

    public function getClientsData(){    
        if(auth()->user()->role == 1){
            $client = Client::with('empname')->get();
        } else {
            $client = Client::with('empname')->where('user_id', auth()->user()->id)->get();
        }
        

        return Datatables::of($client)
        ->addIndexColumn()
        ->addColumn('action', function($client){

            $editUrl = route('clients.edit', $client->id); 
            $action = '';  
            if (auth()->user()->can('client-edit')) {
                $action .= '<a href="'.$editUrl.'" class="edit btn btn-warning btn-sm">Edit</a> '; 
            }
            if (auth()->user()->can('client-delete')) {
                $action .= '<a href="" class="edit btn btn-danger btn-sm confirm-delete" href="javascript:void(0)" data-id="'.$client->id.'"">Delete</a>';
            }
            return $action;
        })  
        ->editColumn('address', function ($client) {
			return $address = $client->address.', '.$client->city;
		})     
        ->rawColumns(['action', 'address'])
        ->make(true); 
    }

    public function edit($id)
    {
        $client = Client::where('id', $id)->first();
        return view($this->folder.'/edit', compact('client'));
    }
   
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'city' => 'required',
            'notes' => 'required'
        ]);

        Client::where('id', $request->id)->update(['user_id' => auth()->user()->id, 'name'=> $request->name, 'email' =>  $request->email, 'address' => $request->address, 'city' => $request->city, 'notes' => $request->notes]);

        return redirect($this->route)->with('success','Client updated successfully');
    }
    
    public function destroy($id)
    {
        if(Client::where('id', $id)->delete()){
            echo json_encode(true);
        }
        else{
            echo json_encode(false);
        }
    }
}
