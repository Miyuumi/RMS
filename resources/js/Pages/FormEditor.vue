<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SideBarMain from '@/Layouts/SideBarMain.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const defineProps =  {
    form: Array,
}

var uuid = 1;
const custom_form = {"uuid": 0, "div":"", "style" : "width: 100%; height: 100%;", "child": []};
const can_remove = ref(false);

var selected_elem = null;
var selected_uuid = 0;
var selected_form = custom_form;

var default_style = "width: 100%; height: 50px; background-color: white; margin-top: 0px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px; padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;";

const add_row = ()=>{
    get_form_by_id(selected_uuid, custom_form).child.push({"uuid":uuid++,"object": "div","class":"flex", "style" : default_style + "background-color: white;", "child":[]});
    set_output();
}

const add_div = ()=>{
    get_form_by_id(selected_uuid, custom_form).child.push({"uuid":uuid++,"object": "div","class":"", "style" : default_style + "background-color: lightgray;", "child":[]});
    set_output();
}

const add_input = ()=>{
    get_form_by_id(selected_uuid, custom_form).child.push({"uuid":uuid++,"object": "input","class":"bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500", "style" : default_style + "background-color: lightgray;", "child":[]});
    set_output();
}


const get_form_by_id = (id, value) => {
    var temp = null;
    var result = null;
    if(value.uuid == id){
        result = value;
    }else{
        for(let x = 0; x < value.child.length; x++){
            temp = get_form_by_id(id, value.child[x]);
            if(temp != null){
                result = temp;
                break;
            }
        };
    }
    return result;
}

const edit_element = (parameter, id) => {
    var value = document.getElementById(id).value;
    
    var edit_form = get_form_by_id(selected_uuid, custom_form);
    var style = edit_form.style;
    var doesExist = style.indexOf(parameter);

    if(doesExist >= 0){
        var extract = style.split(";");
        var value_style = [];
        
        
        extract.forEach((split)=>{
            var temp = {};
            temp[split.substring(0, split.indexOf(":")).trim()] = split.substring(split.indexOf(":")+1).trim();
            value_style.push(temp);
            
        });

        for(let x = 0; x < value_style.length; x++){
            if(value_style[x][parameter]){
                value_style[x][parameter] = value;
            }
        }


        var result_string = "";
        for(let x = 0; x < value_style.length; x++){
            for (const [key, value] of Object.entries(value_style[x])) {
                if(key && value){
                    result_string += key + " : "+value+";";
                }
            }
        }
        edit_form.style = result_string;
        
    }else{
        edit_form.style += parameter+" : "+ value + ";";
    }

    set_output();
    set_last_selected(selected_uuid);
}

const select_element = (e)=>{
    if(selected_elem){
        selected_elem.style.border = "";
    }
    
    selected_elem = e.target;
    selected_uuid = selected_elem.id;
    selected_elem.style.border = "2px solid black";
    can_remove.value = true;
    e.stopPropagation();

    var edit_form = get_form_by_id(selected_uuid, custom_form);
    var style = edit_form.style;

    var extract = style.split(";");
    var value_style = [];

    extract.forEach((split)=>{
        var temp = {};
        temp[split.substring(0, split.indexOf(":")).trim()] = split.substring(split.indexOf(":")+1).trim();
        value_style.push(temp);
    });

    for(let x = 0; x < value_style.length; x++){
        for (const [key, value] of Object.entries(value_style[x])) {
            if(key && value){
                var temp = document.getElementById(key);
                if(temp){
                    temp.value = value;
                }
            }
        }
    }

}

const set_last_selected = (id)=>{
    selected_elem = document.getElementById(id);
    selected_uuid = selected_elem.id;
    selected_elem.style.border = "2px solid black";
    can_remove.value = true;
}

const commit_element = (elem) => {
    var elem_row = document.createElement(elem.object);
    elem_row.setAttribute("class",elem.class);
    elem_row.setAttribute("id",elem.uuid);
    elem_row.style.cssText = elem.style;
    elem_row.addEventListener("click",select_element);

    elem.child.forEach((rows)=>{
        elem_row.appendChild(commit_element(rows));
    });

    return elem_row;
}

const set_output = ()=>{
    can_remove.value = false;
    selected_elem = null;
    var output = document.getElementById("output");
    output.innerHTML = "";
    output.appendChild(commit_element(custom_form));
}


</script>

<template>
    
    <Head title="Form" />
    
    <SideBarMain>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Form Editor
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <div class="w-full flex justify-between">
                            <div style="width: 100%;min-height: 600px; border: 1px solid; background-color: gray" class="m-1 p-1" id="output">
                                
                            </div>
                            <div style="width: 50%; min-height: 600px; border: 1px solid;" class="m-1 p-3" id="controls">
                                <button :onclick="add_row" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Row</button>
                                <button :onclick="add_div" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Container</button>
                                <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Static Text</button>
                                <button :onclick="add_input" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Variable</button>
                                <div style="width: 100%; min-height: 200px; border: 1px solid" class="p-3" id="attribs">
                                    <div class="mb-2">
                                        <label>Size (Width-Height)</label>
                                        <div class="flex">
                                            <input @change="edit_element('width','width')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="width" type="text" value="100%">
                                            <input @change="edit_element('height','height')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="height" type="text" value="25px">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label>Padding (Top-Bottom-Left-Right)</label>
                                        <div class="flex">
                                            <input @change="edit_element('padding-top','padding-top')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="padding-top" type="text" value="0">
                                            <input @change="edit_element('padding-bottom','padding-bottom')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="padding-bottom" type="text" value="0">
                                        </div>
                                        <div class="flex">
                                            <input @change="edit_element('padding-left','padding-left')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="padding-left" type="text" value="0">
                                            <input @change="edit_element('padding-right','padding-right')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="padding-right" type="text" value="0">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label>Margin (Top-Bottom-Left-Right)</label>
                                        <div class="flex">
                                            <input @change="edit_element('margin-top','margin-top')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="margin-top" type="text" value="0">
                                            <input @change="edit_element('margin-bottom','margin-bottom')"class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="margin-bottom" type="text" value="0">
                                        </div>
                                        <div class="flex">
                                            <input @change="edit_element('margin-left','margin-left')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="margin-left" type="text" value="0">
                                            <input @change="edit_element('margin-right','margin-right')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="margin-right" type="text" value="0">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label>Text Size</label>
                                        <input class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="attrib_text_size" type="text" value="12">
                                        <div class="flex">
                                            <button class="m-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Bold</button>
                                            <button class="m-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Italics</button>
                                            <button class="m-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Underline</button>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <button v-if="can_remove == true" class="w-full m-1 bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Remove</button>
                                        <button class="w-full m-1 bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </SideBarMain>
</template>
