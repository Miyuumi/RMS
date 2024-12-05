<script setup>
import Modal from '@/Components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SideBarMain from '@/Layouts/SideBarMain.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const open_modal = ref(false);
const form_id = ref("")
const form_data = ref({})
var template_header = [];
var template_design = {};
var counter = 0;
const form_template = ref({})

const props = defineProps({
    form_id: {
        type: String
    },
});

const get_data = (e) => {
    const id = e.target.id.replaceAll("edit_","");
}

const delete_data = (e) => {
    const id = e.target.id.replaceAll("delete_","");
    const data = {
        "id": props.form_id,
        "del_id": id,
    };
    axios.post("/record_del", data).then(res=>{
        // console.log(res);
        axios.get("/record_get/"+props.form_id).then(res => {
            form_data.value = JSON.parse(res.data.form_data);
        });
    });
}

const add_record = (e) => {
    show_modal();
    setTimeout(()=>{
        document.getElementById("modal_view").appendChild(commit_element(template_design));
    }, 0);
    
}

const save_record = (e) => {
    e.preventDefault();
    const formData = new FormData(document.getElementById("basic_form"));
    var formProps = Object.fromEntries(formData);
    formProps = Object.assign({id: counter}, formProps);
    
    axios.post("/record_add",{
        "data":[formProps],
        "id":props.form_id,
        "count": counter
    }).then(res=>{
        axios.get("/record_get/"+props.form_id).then(res => {
            form_data.value = JSON.parse(res.data.form_data);
        });
        counter += 1;
        show_modal();
    })
}



const commit_element = (elem) => {
    var elem_row = document.createElement(elem.object);
    elem_row.setAttribute("class",elem.class);
    elem_row.setAttribute("name",elem.name);
    elem_row.setAttribute("id",elem.uuid);
    elem_row.style.cssText = elem.style;

    elem.child.forEach((rows)=>{
        elem_row.appendChild(commit_element(rows));
    });

    if(elem.child.length == 0){
        elem_row.innerHTML = elem.value;
    }

    return elem_row;
}

const delete_form = (e) => {

}

const show_modal = ()=>{
    open_modal.value = !open_modal.value;
}

axios.get("/record_get/"+props.form_id).then(res => {
    form_data.value = JSON.parse(res.data.form_data);
    if(res.data.data_count){
        counter = res.data.data_count;
    }
    
});

axios.get("/record_template/"+props.form_id).then(res => {    
    form_template.value = res.data;
    template_header = JSON.parse(res.data.template_data);

});

axios.get("/forms_find/"+props.form_id).then(res=>{    
    if(res.data.design_data){
        template_design = JSON.parse(res.data.design_data);
    }
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
            <div class="max-w-8xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <Modal :show="open_modal">
                        <form name="basic_form" id="basic_form">
                            <div id="modal_view">

                            </div>

                            <div class="flex justify-end p-3">
                                <button type="button" :onclick="save_record" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save</button>
                                <button type="button" :onclick="show_modal" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Cancel</button>
                            </div>
                        </form>
                    </Modal>
                    <div class="p-6 text-gray-900">
                        <div class="p-1 text-gray-900">
                            <button :onclick="add_record" class="mb-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add Data</button>
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="py-3">#</th>
                                        <th v-for="header in template_header" class="py-3">{{ header.name }}</th>
                                        <th class="py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in form_data">
                                        <td v-for="(value, key, index) in data">{{ value }}</td>
                                        <td>
                                            <button disabled :id="'edit_'+data['id']" :onclick="get_data" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit</button>
                                            <button :id="'delete_'+data['id']" :onclick="delete_data" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Delete</button>
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
