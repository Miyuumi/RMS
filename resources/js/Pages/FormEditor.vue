<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SideBarMain from '@/Layouts/SideBarMain.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    form: {
        type: Array
    },
});

const template_data = JSON.parse(props.form[0].template_data);
const form_id = props.form[0].form_id;
var uuid = props.form[0].design_uuid;

var custom_form = {"uuid": 0, "object":"div", "style" : "width: 100%; height: 100%;", "child": [], "value":"", "name":""};
const can_remove = ref(false);
const viewControls = ref(true);

axios.get("/forms_find/"+form_id).then(res=>{    
    if(res.data.design_data){
        custom_form = JSON.parse(res.data.design_data)
        set_output()
    }
});
var selected_elem = null;
var selected_uuid = 0;
var selected_form = custom_form;

var default_style = "width: 100%; height: 50px; font-size: 12px; background-color: white; margin-top: 0px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px; padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;";

const add_row = ()=>{
    get_form_by_id(selected_uuid, custom_form).child.push({"uuid":uuid++,"object": "div","class":"flex flex-row", "style" : default_style + "background-color: white;", "child":[],"value":"", "name":""});
    set_output();
}

const add_col = ()=>{
    get_form_by_id(selected_uuid, custom_form).child.push({"uuid":uuid++,"object": "div","class":"flex flex-col", "style" : default_style + "background-color: white;", "child":[],"value":"", "name":""});
    set_output();
}

const add_div = ()=>{
    get_form_by_id(selected_uuid, custom_form).child.push({"uuid":uuid++,"object": "div","class":"", "style" : default_style + "background-color: lightgray;", "child":[],"value":"", "name":""});
    set_output();
}

const add_label = ()=>{
    get_form_by_id(selected_uuid, custom_form).child.push({"uuid":uuid++,"object": "label","class":"block text-gray-700 text-sm font-bold mb-2", "style" : "", "child":[],"value":"Label", "name":""});
    set_output();
}

const add_input = ()=>{
    get_form_by_id(selected_uuid, custom_form).child.push({"uuid":uuid++,"object": "input","class":"m-2 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500", "style" : "", "child":[],"value":"", "name":""});
    set_output();
}

const add_decor = (style)=>{
    var form = get_form_by_id(selected_uuid, custom_form);
    
    if(form.class.indexOf(style) >= 0){
        form.class = form.class.replaceAll(style,"")
    }else{
        form.class += " " + style
    }

    set_output();
    set_last_selected(selected_uuid);
}

const change_control = () => {
    viewControls.value = !viewControls.value
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

const get_parent = (id, value) => {
    var temp = null;
    var result = null;
    var onChild = false;

    for(let x = 0; x < value.child.length; x++){
        if(value.child[x].uuid == id){
            onChild = true;
            break;
        }
    };

    if(onChild){
        result = value;
    }else{
        for(let x = 0; x < value.child.length; x++){
            temp = get_parent(id, value.child[x]);
            if(temp != null){
                result = temp;
                break;
            }
        };
    }
    return result;
}

const remove_element = () => {
    var result = get_parent(selected_uuid, custom_form)
    // result = null;
    

    for(let x = 0; x < result.child.length; x++){
        if(result.child[x].uuid == selected_uuid){
            result.child.splice(x,1);
            break;
        }
    };

    set_output();
}

const add_value = (id) => {
    var value = document.getElementById(id).value;
    var edit_form = get_form_by_id(selected_uuid, custom_form);
    edit_form.value = value;

    set_output();
    set_last_selected(selected_uuid);
}

const set_name = (e) => {
    var edit_form = get_form_by_id(selected_uuid, custom_form);
    edit_form.name = e.target.innerHTML;

    set_output();
    set_last_selected(selected_uuid);
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

    document.getElementById("text-value").value = edit_form.value;

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
    elem_row.setAttribute("name",elem.name);
    elem_row.setAttribute("id",elem.uuid);
    elem_row.style.cssText = elem.style;
    elem_row.addEventListener("click",select_element);

    elem.child.forEach((rows)=>{
        elem_row.appendChild(commit_element(rows));
    });

    if(elem.child.length == 0){
        elem_row.innerHTML = elem.value;
    }

    return elem_row;
}

const submit = () => {
    
    const data = {
        "id": form_id,
        "design_data":JSON.stringify(custom_form),
        "design_uuid" : uuid
    };

    axios.post("/forms_save",data).then(res=>{
        window.location.href = "/forms"
    });
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
                Form Editor - {{ props.form[0].form_name }}
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
                            <div v-if="viewControls" style="width: 50%; min-height: 600px; border: 1px solid;" class="m-1 p-3" id="controls">
                                <div class="mb-2 flex justify-between">
                                        <h2 style="font-size:30px;" class="font-semibold leading-tight text-gray-800">Form Controls </h2><button @click="change_control" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Data</button>
                                    </div>
                                <div class="flex">
                                    
                                </div>
                                <div class="flex">
                                    <button :onclick="add_row" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Row</button>
                                    <button :onclick="add_col" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Column</button>
                                </div>
                                <div class="flex">
                                    <button :onclick="add_div" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Container</button>
                                </div>
                                <div class="flex">
                                    <button :onclick="add_label" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Label</button>
                                    <button :onclick="add_input" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Variable</button>
                                </div>
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
                                        <input @change="edit_element('font-size','font-size')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="font-size" type="text" value="12px">
                                        <label>Text Value</label>
                                        <input @change="add_value('text-value')" class="m-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="text-value" type="text" value="">
                                        <div class="flex">
                                            <button @click="add_decor('font-bold')" class="m-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Bold</button>
                                            <button @click="add_decor('italic')" class="m-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Italics</button>
                                            <button @click="add_decor('underline')" class="m-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Underline</button>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <button v-if="can_remove == true" :onclick="remove_element" class="w-full m-1 bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Remove</button>
                                        <button @click="submit" class="w-full m-1 bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="!viewControls" style="width: 50%; min-height: 600px; border: 1px solid;" class="m-1 p-3" id="data">
                                <div class="mb-2 flex justify-between">
                                    <h2 style="font-size:30px;" class="font-semibold leading-tight text-gray-800">Form Data </h2><button @click="change_control" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Controls</button>
                                </div>
                                <div class="mb-2">
                                    <div class="w-full" v-for="data in template_data" :key="data.id">
                                        <button @click="set_name" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{data.name}}</button>
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
