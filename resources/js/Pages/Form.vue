<script setup>
import Modal from '@/Components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SideBarMain from '@/Layouts/SideBarMain.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const form_list = ref([]);
const template_list = ref([]);
var form_ctr = 0
const open_modal = ref(false)
const form_id = ref("")
const form_name = ref("")
const form_description = ref("")
const form_template = ref(0)
const form_data = ref([])


const check_out = (e) => {
    var set_id = e.target.id.replaceAll("save_","");
    window.location.href = "/forms_edit/" + set_id;
}

const delete_form = (e) => {
    form_id.value = e.target.id.replace("delete_","");
    axios.get('/forms_delete/'+form_id.value).then(res=>{
        alert("Form Deleted");
        axios.get("/forms_get").then(res=>{
            form_list.value = res.data;
        });

    })
}

const show_modal = ()=>{

    open_modal.value = !open_modal.value;
}

const submit = ()=>{
    var data = {
        "id" : form_id.value,
        "form_name" : form_name.value,
        "form_description" : form_description.value,
        "template_id" : form_template.value
        
    };
    
    axios.post("/forms_add",data).then(res=>{
        window.location.href = "/forms_edit/" + res.data.id
    })
    show_modal();
}


axios.get("/forms_get").then(res=>{
    form_list.value = res.data;
});

axios.get("/template_get").then(res=>{
    template_list.value = res.data;
});
</script>

<template>
    
    <Head title="Forms" />
    
    <SideBarMain>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Forms
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                <Modal :show="open_modal">
                        <div class="p-6">
                            <div class="flex justify-between">
                                <div>Add New Form</div>
                                <button :onclick="show_modal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">X</button>
                            </div>
                            <div>
                                <div class="max-w-sm mx-auto">
                                    <input type="hidden" id="id" v-model="form_id"/>
                                    <div class="mb-5">
                                        <label for="form_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Form Name</label>
                                        <input type="text" id="form_name" v-model="form_name" name="form_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Record Form" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="form_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Form Description</label>
                                        <textarea id="form_description" v-model="form_description" name="form_description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Form used in administrative section."></textarea>
                                    </div>
                                   
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Template</label>
                                    <div class="flex items-start mb-5 border-2 rounded-md p-2">
                                        <select id="users" v-model="form_template" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option selected value = 0>Choose a Template</option>
                                            <option v-for="template in template_list" :key="template.id" :value="template.id">{{ template.template_name }}</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="button" :onclick="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Modal>
                    <div class="p-6 text-gray-900">
                        <div class="p-1 text-gray-900">
                            <button :onclick="show_modal" class="mb-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add Form</button>
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="py-3">Form Name</th>
                                        <th class="py-3">Form Description</th>
                                        <th class="py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in form_list" :key="data.id">
                                        <td>{{ data.form_name }}</td>
                                        <td>{{ data.form_description }}</td>
                                        <td>
                                            <button :id="'save_'+data.id" :onclick="check_out" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View</button>
                                            <button :id="'delete_'+data.id" :onclick="delete_form" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Delete</button>
                                        </td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SideBarMain>
</template>
