<script setup>
import Modal from '@/Components/Modal.vue';
import SideBarMain from '@/Layouts/SideBarMain.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const template_list = ref([])

var template_ctr = 0
const modal_title = ref("Add New Template");
const template_id = ref("")
const template_name = ref("")
const template_description = ref("")
const template_data = ref([{"id":template_ctr++,"name":""}])
const template_temp = ref("");


const check_out = (e) => {
    template_id.value = e.target.id.replace("save_","");
    modal_title.value = "Edit Template";
    template_data.value = [];
    axios.get('/template_find/'+template_id.value).then(res=>{
        template_name.value = res.data.template_name;
        template_description.value = res.data.template_description;
        template_data.value = JSON.parse(res.data.template_data);
        show_modal();
    })
}

const delete_template = (e) => {
    template_id.value = e.target.id.replace("delete_","");
    axios.get('/template_delete/'+template_id.value).then(res=>{
        alert("Template Deleted");
        axios.get("/template_get").then(res=>{
            template_list.value = res.data;
        });

    })
}

const new_template = ()=>{
    template_id.value = ""
    template_name.value = "";
    template_description.value = "";
    template_data.value = [{"id":template_ctr++,"name":""}];
    modal_title.value = "Add New Template";
    show_modal()
}

const del_new_attribute = (e)=>{
    const del_id =  e.target.id.replace("del_","");
    for(let x = 0; x <  template_data.value.length; x++){
        if(template_data.value[x].id == del_id){
            template_data.value.splice(x,1);
        }
    }
}

const open_modal = ref(false);

const add_new_ctr = ()=>{
    
    template_data.value.push({"id":template_ctr++,"name":""})
}

const set_new_ctr = ()=>{
    template_temp.value = 
    console.log(template_temp.value);
}

const show_modal = ()=>{
    open_modal.value = !open_modal.value;
}

const add_template = ()=>{
    const data = {
        "id" : template_id.value,
        "template_name" : template_name.value,
        "template_description" : template_description.value,
        "template_data" : JSON.stringify(template_data.value),
    };

    axios.post("/template_add",data).then(res=>{
        show_modal();
    });

    axios.get("/template_get").then(res=>{
        template_list.value = res.data;
    });
}

axios.get("/template_get").then(res=>{
    template_list.value = res.data;
});
</script>

<template>
    <Head title="Template" />

    <SideBarMain>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Template
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <Modal :show="open_modal">
                        <div class="p-6">
                            <div class="flex justify-between">
                                <div>{{ modal_title }}</div>
                                <button :onclick="show_modal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">X</button>
                            </div>
                            <div>
                                <div class="max-w-sm mx-auto">
                                    <div class="mb-5">
                                        <label for="template_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Template Name</label>
                                        <input type="text" id="template_name" v-model="template_name" name="template_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Simple Record" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="template_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Template Description</label>
                                        <textarea id="template_description" v-model="template_description" name="template_description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Recording name and age of people."></textarea>
                                    </div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Template Attributes</label>
                                    <div class="flex items-start mb-5">
                                        
                                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <tbody class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr v-for="item in template_data" :key="item.id">
                                                    <td><input type="text" v-model="item.name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Attribute" /></td>
                                                    <td><button type="button" :id="'del_'+item.id" :onclick="del_new_attribute" class="ml-5 w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                    <div class="flex">
                                        <button type="button" :onclick="add_new_ctr" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add Attribute</button>
                                    </div>
                                    
                                    
                                    <div class="flex justify-end">
                                        <button type="button" :onclick="add_template" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Modal>
                    <div class="p-1 text-gray-900">
                        <button :onclick="new_template" class="mb-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add Template</button>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="py-3">Template Name</th>
                                    <th class="py-3">Template Description</th>
                                    <th class="py-3">Actions</th>
                                </tr>
                            </thead>
                            <div class="mt-3"></div>
                            <tbody >
                                <tr v-for="data in template_list">
                                    <td>{{ data.template_name }}</td>
                                    <td>{{ data.template_description }} Description</td>
                                    <button :id="'save_'+data.id" :onclick="check_out" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View</button>
                                    <button :id="'delete_'+data.id" :onclick="delete_template" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Delete</button>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </SideBarMain>
</template>
