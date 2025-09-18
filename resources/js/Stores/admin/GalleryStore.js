import { defineStore } from 'pinia'
import {ref} from "vue";
import iziToast from "izitoast";

import i18n from '../../i18n';
const t = i18n.global.t;

export const useGalleryStore = defineStore('Gallery', {
    state: () => {
        return {
            // constants start
            PageTitle: t('pages.Gallery'),
            // constants end
            media: {},
            dataApiLink: '',
            basePath: '', // basePath is used when we are reusing fileManagerController back-end through gallery actions panel(copyCut, delete, etc).
            openedMediumActionPanelId: null,
            showExtraInfo: false,
            // below is for filter modal
            showFiltersModal: false,
            filters: {
                fileName: '',
                user: null,
                fileType: 'all',
                uploadDate: 'all',
            },
            filteringData: {
                allUsers: {},
            },
            // below is for upload modal
            showUploadModal: false,
            selectedUploadMedia: [],
            isUploading: false,
            showFullScreenLoader: false,
        }
    },
    actions: {
        openActionsMethod(data){
            // opens/closes the actions panel of each data row
            if (data.id === this.openedMediumActionPanelId){
                this.openedMediumActionPanelId = 0;
            }else {
                this.openedMediumActionPanelId = data.id;
            }
        },
        openFiltersModal(){
            this.showFiltersModal = true;
        },
        closeFiltersModal(){
            this.showFiltersModal = false;
        },
        openUploadModal(){
            this.showUploadModal = true;
        },
        closeUploadModal(){
            this.showUploadModal = false;
        },
        setMediaData(data){
            this.media = JSON.parse(JSON.stringify(data));
        },
        resetFilters(){
            this.filters = {
                fileName: '',
                userName: 'all',
                fileType: 'all',
                uploadDate: 'all'
            };
        },
        updateMedia(){
            this.showFullScreenLoader = true;
            axios.post('/admin/gallery/indexDataApi', {
                filters: this.filters,
            }).then((res)=>{
                    this.showFullScreenLoader = false;
                    this.media = res.data;
                });
        },
        // below is for uploading
        setUploadMedia(files){
            this.selectedUploadMedia = this.selectedUploadMedia.concat(Object.values(files))

            // it also sets default upload percentage
            this.selectedUploadMedia.map(item =>{
                item.uploadPercent = ref(0);
            });
        },
        removeFileFromSelectedUploadMedia(index){
            if (this.isUploading) return;
            this.selectedUploadMedia.splice(index, 1);
        },
        uploadMedia(){
            this.isUploading = true;
            this.selectedUploadMedia.map(item =>{
                let form = new FormData();
                // form.append('path', this.path);
                form.append('medium', item);

                let config = {
                    onUploadProgress(progressEvent) {
                        item.uploadPercent.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        // console.log(progressEvent);
                    },
                };

                axios.post('/admin/gallery/upload', form, config)
                    .then(res => {
                        if (res.status === 200){
                            iziToast.success({title: 'موفقیت!', message: 'فایل با موفقیت آپلود شد!'});
                            this.updateMedia();
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
        removeFileFromUploadList(item){
            this.selectedUploadMedia = this.selectedUploadMedia.filter(item2=>{
                return item.name !== item2.name;
            });
            if (this.selectedUploadMedia.length === 0){
                this.isUploading = false;
            }
        },
    },
})
