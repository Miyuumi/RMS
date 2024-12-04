<?php

namespace App\Http\Controllers;

use App\Models\Form;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Inertia::render('Form');
    }

    public function view($id)
    {
        //
        $form = Form::
        select("templates.*","forms.*","forms.id as form_id")
        ->leftjoin("templates","forms.template_id","=","templates.id")
        ->where("forms.id", $id)
        ->get();
        return Inertia::render('FormEditor',["form"=>$form]);
    }

    public function new()
    {
        //
        return Inertia::render('FormEditor');
    }

    public function get()
    {
        //
        return Form::get();
    }

    public function find($id)
    {
        //
        return Form::find($id);
    }

    public function create(Request $request)
    {
        //
        if($request->post("id")){
            $form = Form::findorfail($request->post("id"));
            if(!$form){
                $form = new Form();
                $form->created_by = Auth::user()->id;
            }else{
                $form->updated_by = Auth::user()->id;
            }
        }else{
            $form = new Form();
            $form->created_by = Auth::user()->id;
        }
        
        $form->form_name = $request->post("form_name");
        $form->form_description = $request->post("form_description");
        $form->template_id = $request->post("template_id");
        
        $form->save();

        return $form;
        // return redirect("/forms_edit/".$form->id);
    }

    public function save(Request $request)
    {
        
        $form = Form::findorfail($request->post("id"));
        $form->updated_by = Auth::user()->id;
        $form->design_data = $request->post("design_data");
        $form->design_uuid = $request->post("design_uuid");
        
        $form->save();
    }

    public function delete($id)
    {
        //
        $group = Form::find($id);
        $group->delete();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        //
    }
}
