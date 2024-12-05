<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Template;
use App\Models\GroupUser;
use App\Models\GroupTemplate;
use App\Models\FormData;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FormDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        //\
        $form = Form::find($id);
        $group = GroupUser::where("group_templates.template_id", $form->template_id)
        ->where("group_users.user_id", Auth::user()->id)
        ->leftjoin("group_templates","group_templates.group_id","=","group_users.group_id")
        ->first();
        if(!$group){
            return Inertia::render('Dashboard');
        }
        if($form){
            $form_data = FormData::where("form_id",$id)->first();
            if(!$form_data){
                $form_data = new FormData();
                $form_data->form_id = $id;
                $form_data->created_by = Auth::user()->id;
                $form_data->save();
            }
            return Inertia::render('Record',["form_id"=>$id]);
        }else{
            return Inertia::render('Dashboard');
        }
        
        
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $form = FormData::where("form_id",$request->post("id"))->first();
        if(!$form){
            $form = new FormData();
            $form->created_by = Auth::user()->id;
            $form->form_data = $request->post("data");
            $form->data_count = $request->post("count") + 1;
        }else{
            $form->updated_by = Auth::user()->id;
            
            if($form->form_data){
                $old_form_data = json_decode(strval($form->form_data));
                $added_form_data = $request->post("data");
                $form_data = array_merge($old_form_data, $added_form_data);
            }else{
                $form_data = $request->post("data");
            }
            $form->data_count = $request->post("count") + 1;
            $form->form_data = $form_data;
        }

        $form->save();
    }

    public function delete(Request $request){
        $form = FormData::where("form_id",$request->post("id"))->first();
        $id = $request->post("del_id");
       
        $form_data = json_decode(strval($form->form_data));
        foreach($form_data as $data){
            
            if($data->id == $id){
               
                array_splice($form_data, array_search($data, $form_data ),1);
                break;
            }
        }
        
        $form->form_data = $form_data;
        $form->save();
        
    }


     public function get($id)
    {
        //
        return FormData::where("form_id",$id)->first();
    }

    public function template($id)
    {
        //
        $form = Form::find($id);
        return Template::find($form->template_id);
    }

    public function store(Request $request)
    {
        //
        $form = FormData::where("form_id",$request->post("id"))->first();
        $form->updated_by = Auth::user()->id;
        $form->form_data = $request->post("data");
        $form->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(FormData $formData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormData $formData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormData $formData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormData $formData)
    {
        //
    }
}
