<template>
    <div class="allCreateForm bg-light grid grid-cols-[1fr,20rem] gap-8 p-4">
        <div
            v-if="store.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[101]"
        >
            <Loader class="rounded-lg" :isActive="store.showFullScreenLoader"/>
        </div>

        <div class="right">
            <div class="bg-white rounded-lg p-4">
                <div class="">
                    <SimpleTextInput
                        :isEssential="true"
                        label="نام"
                        errorKeyName="name"
                        v-model="store.newData.name"
                        :errors="errors"
                    />
                </div>

                <div class="mt-7">
                    <SimpleTextInput
                        :isEssential="false"
                        :label="$t('forms.common.slug')"
                        errorKeyName="slug"
                        v-model="store.newData.slug"
                        :errors="errors"
                    />
                </div>

                <div class="w-[500px] mt-4 text-start">
                    <h4 class="text-sm font-medium mb-1 text-[#3f4254] inline-block">هنرمند</h4>
                    <span class="text-danger">*</span>
                    <ItemSelector v-model="store.newData.artist" searchLink="/admin/artists/indexDataApi"/>
                    <p v-if="errors['artist_id']" class="text-danger">{{ errors['artist_id'][0] }}</p>
                </div>

                <div class="mt-7">
                    <h4 class="text-sm mb-2 text-[#3f4254]">
                        تاریخ انتشار
                    </h4>
                    <DatePicker v-model="store.newData.release_date"/>
                    <p v-if="errors['release_date']" class="text-xs text-danger font-light">{{ errors.release_date[0] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 mt-4">
                <TracksHandler :store="store" />
            </div>
        </div>

        <div class="left space-y-4">
            <MediumPicker title="آلبوم" v-model="store.photo" />

            <div class="mt-8 bg-white rounded-lg p-4">
                <div class="grid grid-cols-[1fr,auto] gap-[3.8rem] mt-4">
                    <SelectBasic
                        v-model="store.newData.is_published"
                        :options-array="{
                            0: $t('forms.articles.draft'),
                            1: $t('forms.articles.published'),
                        }"
                        :showSearch="false"
                    />

                    <button class="btn btn-primary btn-active-primary" @click="createAlbumMethod">
                        {{$t('forms.common.save')}}
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {computed, onBeforeMount, onMounted, onUpdated, ref} from 'vue';
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import SelectMediumModal from "../../../Components/admin/Widgets/SelectMediumModal.vue";
import iziToast from "izitoast";
import DatePicker from "vue3-persian-datetime-picker";
import DocumentTextEditor from "../../../Components/admin/Widgets/Input/Texts/DocumentTextEditor.vue";
import {useCreateAlbumStore} from "../../../Stores/admin/Albums/createStore";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import SelectBasic from "../../../Components/admin/Widgets/Input/Selects/SelectBasic.vue";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import MediumPicker from "@/Components/admin/Widgets/Input/MediumPicker.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import i18n from '../../../i18n';
import ItemSelector from "@/Components/admin/Widgets/Input/ItemSelector.vue";
import TracksHandler from "@/Components/admin/AlbumsAndPlaylists/TracksHandler.vue";

export default {
    name: "CreateAlbumForm",
    layout: AdminLayout,
    components:{
        TracksHandler,
        ItemSelector,
        SimpleTextInput,
        MediumPicker,
        Loader,
        SelectBasic, DocumentTextEditor, SelectMediumModal,
        SvgComponent, DatePicker},
    props:{
        isEdit: {
            type: Boolean,
            Default: false,
        },
        formerData: {
            type: Object,
            Default: null,
        },
        allCategories: Object,
        allTags: Object,
    },
    setup(props){
        const store = useCreateAlbumStore();
        const headerStore = useHeaderStore();
        const errors = ref({});
        const t = i18n.global.t;

        const profilePreviewComputed = computed(()=>{
            return store.photo != null ?
                URL.createObjectURL(store.photo) : null;
        });

        const createAlbumMethod = ()=>{
            store.showFullScreenLoader = true;
            let trackIds = getItemIds(store.newData.tracks);

            let data = {
                name: store.newData.name,
                slug: store.newData.slug,
                release_date: store.newData.release_date,
                is_published: store.newData.is_published,
                photo_id: store.photo?.id,
                artist_id: store.newData.artist?.id,
                tracks: trackIds,
            };

            if (props.isEdit){
                axios.put(`/admin/albums/${props.formerData.id}`, data)
                    .then(res=>{
                        store.showFullScreenLoader = false;
                        if (res.status === 200){
                            errors.value = {};
                            iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});
                        }
                    }).catch((_error) => {
                    store.showFullScreenLoader = false;
                    errors.value = _error.response.data.errors;
                    iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                });
            }
            else {
                axios.post('/admin/albums', data)
                    .then(res=>{
                        store.showFullScreenLoader = false;
                        if (res.status === 200){
                            store.resetFormValues();
                            errors.value = {};
                            iziToast.success({title: t('toasts.success'), message: t('toasts.createdSuccessfully'),});
                        }
                    }).catch((_error) => {
                    store.showFullScreenLoader = false;
                    errors.value = _error.response.data.errors;
                    iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                });
            }
        };

        function getItemIds(items){
            let output = [];
            for (const itemsKey in items) {
                output.push(items[itemsKey].id);
            }
            return output;
        }

        onBeforeMount(()=>{
            headerStore.title = props.isEdit ? store.PageTitleEdit : store.PageTitleCreate;
        })

        onMounted(()=>{
            if (props.isEdit){
                store.newData = props.formerData;
                store.photo = props.formerData.photo;
            }else {
                store.newData = {};
                store.photo = null;
            }
        });

        return{
            store,
            errors,
            profilePreviewComputed,
            createAlbumMethod,
            headerStore
        }
    }
}

</script>

<style>
.disabledDatepicker input{
    /* this is used for disabled style on persian-datepicker */
    background-color: #E9ECEF!important;
}
</style>
