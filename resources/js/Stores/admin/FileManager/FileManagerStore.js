import { defineStore } from 'pinia'
import {ref} from "vue";
import iziToast from "izitoast";

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useFileManagerStore = defineStore('FileManager', {
    state: () => {
        return {
            // constants start
            PageTitle: t('pages.FileManager'),
            // constants end
            openedTab: 'FileManagerTab',
            showCreateDirModal: false,
            tableData: {},
            searchedTableData: [],
            path: '',
            searchText: '',
            // below is for uploading
            showUploadFileModal: false,
            selectedUploadFiles: [],
            isUploading: false,
            // below is for selecting multiple data
            selectedResults: [],
            areAllSelected: false,
            // below is for copy and pasting
            clipboard:{
                type: '', // 'copy' or 'cut'
                results: [],
            },
            // below is for renaming
            showRenameModal: false,
            toBeEdited: {},
            showFullScreenLoader: false,
        }
    },
    actions: {
        // below is for closing and opening modals
        closeCreateDirModal(){
            this.showCreateDirModal = false;
        },
        closeUploadFileModal(){
            this.showUploadFileModal = false;
        },
        openCreateDirModal(){
            this.showCreateDirModal = true;
        },
        openUploadFileModal(){
            this.showUploadFileModal = true;
        },
        // below are table actions
        setTableData(data){
            this.tableData = JSON.parse(JSON.stringify(data));
            this.searchedTableData = JSON.parse(JSON.stringify(data));
        },
        updateTableData(){
            axios.post('/admin/fileManager/getPathResults', {
                path: this.path,
            }).then((res)=>{
                    this.tableData = res.data.results;
                    this.searchedTableData = res.data.results;
                });
        },
        // below is for selecting multiple data
        selectAll(){
            if (this.tableData.length === this.selectedResults.length){
                this.selectedResults.splice(0, this.selectedResults.length);
            }else{
                // it empties the array
                this.selectedResults.splice(0, this.selectedResults.length);
                // then pushes all the values
                this.tableData.forEach( (item) => {
                    this.selectedResults.push(item);
                });
            }
        },
        checkIfAllAreSelected(){
            this.areAllSelected = this.selectedResults.length === this.tableData.length;
        },
        // below is for uploading
        setUploadFiles(files){
            this.selectedUploadFiles = this.selectedUploadFiles.concat(Object.values(files))

            // it also sets default upload percentage
            this.selectedUploadFiles.map(item =>{
                item.uploadPercent = ref(0);
            });
        },
        uploadFiles(){
            this.isUploading = true;
            this.selectedUploadFiles.map(item =>{
                let form = new FormData();
                form.append('path', this.path);
                form.append('file', item);

                let config = {
                    onUploadProgress(progressEvent) {
                        item.uploadPercent.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        // console.log(progressEvent);
                    },
                };

                axios.post('/admin/fileManager/upload', form, config)
                    .then(res => {
                        if (res.status === 200){
                            iziToast.success({title: 'موفقیت!', message: 'فایل با موفقیت آپلود شد!'});
                            this.updateTableData();
                            this.removeFileFromUploadList(item);
                        }
                    }).catch(error => {
                        if (error){
                            iziToast.error({title: 'خطا!', message: 'آپلود فایل انجام نشد.'});
                            this.removeFileFromUploadList(item);
                        }
                    });
            });
        },
        removeFileFromSelectedUploadMedia(index){
            if (this.isUploading) return;
            this.selectedUploadFiles.splice(index, 1);
        },
        removeFileFromUploadList(item){
            this.selectedUploadFiles = this.selectedUploadFiles.filter(item2=>{
                return item.name !== item2.name;
            });
            if (this.selectedUploadFiles.length === 0){
                this.isUploading = false;
            }
        },
        // below is for copy and pasting
        copySelected(){
            this.clipboard.type = 'copy';
            this.clipboard.results = JSON.parse(JSON.stringify(this.selectedResults));
            this.selectedResults.splice(0, this.selectedResults.length);
            this.checkIfAllAreSelected(); // fixes the selectAll checkbox bug
        },
        cutSelected(){
            this.clipboard.type = 'cut';
            this.clipboard.results = JSON.parse(JSON.stringify(this.selectedResults));
            this.selectedResults.splice(0, this.selectedResults.length);
            this.checkIfAllAreSelected(); // fixes the selectAll checkbox bug
        },
        pasteClipboard(){
            if (this.clipboard.results.length === 0){
                iziToast.info({title: 'توجه!', message: 'فایلی برای جایگذاری انتخاب نشده است!'});
                return;
            }

            this.showFullScreenLoader = true;
            axios.post('/admin/fileManager/copyCut', {
                type: this.clipboard.type,
                files: this.clipboard.results,
                destination: this.path,
            }).then(res => {
                this.showFullScreenLoader = false;
                if (res.status === 200){
                    iziToast.success({title: 'موفقیت!', message: 'فایل با موفقیت جایگذاری شد!'});
                    this.updateTableData();
                    this.clipboard.type = '';
                    this.clipboard.results = [];
                }
            });
        },
        // below is for renaming
        openRenameModal(result){
            this.toBeEdited = result;
            this.showRenameModal = true;
        },
        closeRenameModal(){
            this.toBeEdited = {};
            this.showRenameModal = false;
        }
    },
})
