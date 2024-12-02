<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Inertia::render('Template');
    }

    public function get()
    {
        //
        return Template::get();
    }
    
    public function find($id)
    {
        //
        return Template::find($id);
    }

    public function delete($id)
    {
        //
        $group = Template::find($id);
        $group->delete();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        if($request->post("id")){
            $template = Template::findorfail($request->post("id"));
            if(!$template){
                $template = new Template();
            }
        }else{
            $template = new Template();
        }
        
        $template->template_name = $request->post("template_name");
        $template->template_description = $request->post("template_description");
        $template->template_data = $request->post("template_data");
        $template->save();

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
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        //
    }
}
