<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\GroupTemplate;

use Inertia\Inertia;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Inertia::render('Group');
    }

    public function get()
    {
        //
        return Group::get();
    }

    public function find($id)
    {
        //
        return Group::find($id);
    }

    public function delete($id)
    {
        //
        $group = Group::find($id);
        $group->delete();
    }
    
    public function create(Request $request)
    {
        //
        if($request->post("id")){
            $group = Group::findorfail($request->post("id"));
            if(!$group){
                $group = new Group();
            }
        }else{
            $group = new Group();
        }
        
        $group->group_name = $request->post("group_name");
        $group->group_description = $request->post("group_description");
        $ctr = 0;
        if($request->post("user_".$ctr)){
            $gr_user = GroupUser::where("group_id",$group->id);
            $gr_user->delete();
        }

        while(true){
            
            if($request->post("user_".$ctr)){
                $user = $request->post("user_".$ctr);
                $g_user = GroupUser::where("user_id",$user)->where("group_id",$group->id)->first();
                if($g_user){
                    $g_user->group_id = $group->id;
                    $g_user->user_id = $user;
                    $g_user->save();
                }else{
                    $g_user = new GroupUser();
                    $g_user->group_id = $group->id;
                    $g_user->user_id = $user;
                    $g_user->save();
                }
            }else{
                break;
            }
            $ctr += 1;
            if($ctr > 100){
                break;
            }
        }

        $ctr = 0;

        if($request->post("template_".$ctr)){
            $gr_user = GroupTemplate::where("group_id",$group->id);
            $gr_user->delete();
        }
        
        while(true){
            
            if($request->post("template_".$ctr)){
                $user = $request->post("template_".$ctr);
                $g_user = GroupTemplate::where("template_id",$user)->where("group_id",$group->id)->first();
                if($g_user){
                    $g_user->group_id = $group->id;
                    $g_user->template_id = $user;
                    $g_user->save();
                }else{
                    $g_user = new GroupTemplate();
                    $g_user->group_id = $group->id;
                    $g_user->template_id = $user;
                    $g_user->save();
                }
            }else{
                break;
            }
            $ctr += 1;
            if($ctr > 100){
                break;
            }
        }

        $group->save();

    }

    public function group_user($id)
    {
        //
       return  GroupUser::where("group_id",$id)->join("users","users.id","=","group_users.user_id")->get();
    }

    public function group_template($id)
    {
        //
       return  GroupTemplate::where("group_templates.group_id",$id)->join("templates","templates.id","=","group_templates.template_id")->get();
    }

    public function user_group($id)
    {
        //
       return  GroupUser::where("user_id",$id)->join("users","users.id","=","group_users.user_id")->get();
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
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
}
