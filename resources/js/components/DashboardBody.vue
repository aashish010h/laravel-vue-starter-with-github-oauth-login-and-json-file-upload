<script setup>
import Axios from "../axios/Axios";
import { FilterMatchMode, FilterOperator } from "primevue/api";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import { ref, onMounted, reactive } from "vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Divider from "primevue/divider";
import Dialog from "primevue/dialog";
import FileUpload from "primevue/fileupload";
import { useToast } from "primevue/usetoast";

//state required for this componenet
const toast = useToast();
const files = ref();
const filters = ref();
const loadingFiles = ref(false);
const showFileUploadModal = ref(false);
//form field for upaloding the file
const fileForm = reactive({ name: "", file: null });
const uploadingFile = ref(false);

//fetch all the files initially for showing in the data tables
const fetchFiles = () => {
    loadingFiles.value = true;
    Axios.get("files")
        .then((res) => {
            files.value = res.data.data.files;
            loadingFiles.value = false;
        })
        .catch((err) => {
            console.log(err);
            loadingFiles.value = false;
        });
};


//initializing the filter for the data table , provided by the prime vue datatable
const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        title: {
            operator: FilterOperator.AND,
            constraints: [
                { value: null, matchMode: FilterMatchMode.STARTS_WITH },
            ],
        },
    };
};
//call iniit function
initFilters();

//to show to add file modal for adding the file
const handleAddFile = () => {
    showFileUploadModal.value = true;
};

//handle file upload and save the file to state from the event
const onUpload = (e) => {
    fileForm.file = e.files[0];
};

//function to store the file to the api with uploading state
const submitFile = () => {
    uploadingFile.value = true;
    //api call for sending the form data to backend for storing to ta db
    Axios.post("uploadFile", fileForm)
        .then((res) => {
            //showing the toast and seting loader to false
            uploadingFile.value = false;
            toast.add({
                severity: "info",
                summary: "Added !! ",
                detail: "File has been added successfully.",
                life: 3000,
            });
            //resetting the form state
            fileForm.name=""
            fileForm.file = null
            //hiding the form modal
            showFileUploadModal.value = false;

            //fetch the files again after adding
            fetchFiles();
        })
        .catch((err) => {
            //hadnling the error and showing the vlaidaiton messsage is any error occours during form submit
            uploadingFile.value = false;
            toast.add({
                severity: "error",
                summary: "Error",
                detail: err.response.data.message,
                life: 3000,
            });
        });
};

//fetch all the files when user comes to dashboard
onMounted(() => {
    fetchFiles();
});
</script>
<template>
    <div class="mt-3">
        <Dialog
            v-model:visible="showFileUploadModal"
            modal
            header="Upload File"
            :style="{ width: '45rem' }"
        >
            <form @submit.prevent="submitFile">
                <div class="form-group">
                    <label for="file">File Name</label>
                    <InputText v-model="fileForm.name" />
                </div>
                <div class="form-group">
                    <label for="file">File</label>
                    <FileUpload mode="basic" name="file" @select="onUpload" />
                </div>
                <Button
                    class="mt-3"
                    type="submit"
                    label="Submit"
                    severity="info"
                    :loading="uploadingFile"
                />
            </form>
        </Dialog>

        <Button
            label="Add File"
            severity="warning"
            rounded
            @click="handleAddFile"
        />

        <Divider />
        <DataTable
            v-model:filters="filters"
            :value="files"
            paginator
            showGridlines
            :rows="10"
            dataKey="id"
            filterDisplay="menu"
            :loading="loadingFiles"
            :globalFilterFields="['name']"
        >
            <template #header>
                <div class="flex justify-content-end">
                    <span class="p-input-icon-left">
                        <!-- <i class="pi pi-search"></i> -->
                        <InputText
                            v-model="filters['global'].value"
                            placeholder="Search files"
                        />
                    </span>
                </div>
            </template>

            <Column header="S.N" headerStyle="width:3rem">
                <template #body="slotProps">
                    {{ slotProps.index + 1 }}
                </template>
            </Column>

            <Column
                field="name"
                header="File Name"
                sortable
                sortField="name"
                style="min-width: 14rem"
            >
                <template #body="{ data }">
                    {{ data.name }}
                </template>
            </Column>

            <Column
                field="Operation"
                header="Action"
                headerStyle="min-width:12rem;"
            >
                <template #body="slotProps">
                    <a :href="`/export/${slotProps.data.id}`">
                        <Button
                            severity="secondary"
                            label="Export as excel"
                            size="small"
                            icon="pi pi-file-excel"
                            class="mr-1"
                        />
                    </a>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
<style>
.form-group {
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
}
</style>
