<?php

namespace App\Http\Controllers;

use App\Models\Form;

use Inertia\Inertia;
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
        $form = Form::find($id);
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

    public function delete($id)
    {
        //
        $group = Form::find($id);
        $group->delete();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
