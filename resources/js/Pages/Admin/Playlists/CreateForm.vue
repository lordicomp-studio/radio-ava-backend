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
                        :label="$t('forms.common.title')"
                        errorKeyName="title"
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
            </div>

            <div class="bg-white rounded-lg p-4 mt-4">
                <TracksHandler :store="store" />
            </div>
        </div>

        <div class="left space-y-4">
            <MediumPicker title="پلی لیست" v-model="store.photo" />

            <TagCategorySelector
                :Header="$t('pages.Categories')"
                :_dataList="allCategories"
                v-model="store.newData.categories"
                :createButtonEvent="openCreateCategoryModal"
                searchLink="/admin/categories/indexDataApi"
            />

            <TagCategorySelector
                :Header="$t('pages.Tags')"
                :_dataList="allTags"
                v-model="store.newData.tags"
                :createButtonEvent="openCreateTagModal"
                searchLink="/admin/tags/indexDataApi"
            />

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

                    <button class="btn btn-primary btn-active-primary" @click="createPlaylistMethod">
                        {{$t('forms.common.save')}}
                    </button>
                </div>
            </div>

        </div>

        <CategoryCreateModal @itemCreated="addCreatedCategory"/>
        <TagCreateModal @itemCreated="addCreatedTag"/>
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
import TagCategorySelector from "../../../Components/admin/Widgets/TagCategorySelector.vue";
import CategoryCreateModal from "../../../Components/admin/Categories/CreateModal.vue"
import TagCreateModal from "../../../Components/admin/Tags/CreateModal.vue";
import {useCreatePlaylistStore} from "../../../Stores/admin/Playlists/createStore";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import {useCategoryIndexStore} from "../../../Stores/admin/Categories/CategoriesIndexStore";
import {useTagIndexStore} from "@/Stores/admin/Tags/TagsIndexStore";
import SelectBasic from "../../../Components/admin/Widgets/Input/Selects/SelectBasic.vue";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import MediumPicker from "@/Components/admin/Widgets/Input/MediumPicker.vue";
import ResizableTextArea from "../../../Components/admin/Widgets/Input/Texts/ResizableTextArea.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import i18n from '../../../i18n';
import TracksHandler from "@/Components/admin/AlbumsAndPlaylists/TracksHandler.vue";

export default {
    name: "CreatePlaylistForm",
    layout: AdminLayout,
    components:{
        TracksHandler,
        SimpleTextInput,
        ResizableTextArea,
        MediumPicker,
        Loader,
        SelectBasic, TagCategorySelector, DocumentTextEditor, SelectMediumModal,
        SvgComponent, CategoryCreateModal, TagCreateModal, DatePicker},
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
        const store = useCreatePlaylistStore();
        const headerStore = useHeaderStore();
        const errors = ref({});
        const t = i18n.global.t;

        const profilePreviewComputed = computed(()=>{
            return store.photo != null ?
                URL.createObjectURL(store.photo) : null;
        });

        const createPlaylistMethod = ()=>{
            store.showFullScreenLoader = true;
            let categoryIds = getItemIds(store.newData.categories);
            let tagIds = getItemIds(store.newData.tags);
            let trackIds = getItemIds(store.newData.tracks);

            let data = {
                name: store.newData.name,
                slug: store.newData.slug,
                is_published: store.newData.is_published,
                photo_id: store.photo?.id,
                tags: tagIds,
                categories: categoryIds,
                tracks: trackIds,
            };

            if (props.isEdit){
                axios.put(`/admin/playlists/${props.formerData.id}`, data)
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
                axios.post('/admin/playlists', data)
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

        const categoryStore = useCategoryIndexStore();
        const openCreateCategoryModal = ()=>{
            categoryStore.showCreateModal = !categoryStore.showCreateModal;
        }
        const addCreatedCategory = (data)=>{
            if (!store.newData.categories){
                store.newData.categories = [];
            }
            store.newData.categories.push(data);
        }


        const tagStore = useTagIndexStore();
        const openCreateTagModal = ()=>{
            tagStore.showCreateModal = !tagStore.showCreateModal;
        }
        const addCreatedTag = (data)=>{
            if (!store.newData.tags){
                store.newData.tags = [];
            }
            store.newData.tags.push(data);
        }

        const useRealDate = ref(true);

        return{
            store,
            errors,
            profilePreviewComputed,
            createPlaylistMethod,
            categoryStore,
            openCreateCategoryModal,
            openCreateTagModal,
            useRealDate,
            addCreatedCategory,
            addCreatedTag,
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
