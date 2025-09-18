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

                <div class="mt-7">
                    <h4 class="text-sm mb-2 text-[#3f4254]">
                        {{ $t('forms.common.description') }}
                    </h4>
                    <ResizableTextArea v-model="store.newData.description" :placeholder="`${$t('forms.common.description')}...`"/>
                    <p v-if="errors['description']" class="text-xs mt-1 text-danger font-light">{{ errors.description[0] }}</p>
                </div>

                <div class="mt-7">
                    <h4 class="text-sm mb-2 text-[#3f4254]">
                        تاریخ تولد
                    </h4>
                    <DatePicker v-model="store.newData.birth_date"/>
                    <p v-if="errors['birth_date']" class="text-xs text-danger font-light">{{ errors.birth_date[0] }}</p>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary btn-active-primary" @click="createArtistMethod">
                    {{$t('forms.common.save')}}
                </button>
            </div>
        </div>

        <div class="left space-y-4">
            <MediumPicker title="هنرمند" v-model="store.photo" />

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
import {useCreateArtistStore} from "../../../Stores/admin/Artists/createStore";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import {useCategoryIndexStore} from "../../../Stores/admin/Categories/CategoriesIndexStore";
import {useTagIndexStore} from "@/Stores/admin/Tags/TagsIndexStore";
import SelectBasic from "../../../Components/admin/Widgets/Input/Selects/SelectBasic.vue";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import MediumPicker from "@/Components/admin/Widgets/Input/MediumPicker.vue";
import ResizableTextArea from "../../../Components/admin/Widgets/Input/Texts/ResizableTextArea.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import i18n from '../../../i18n';
import ItemSelector from "@/Components/admin/Widgets/Input/ItemSelector.vue";

export default {
    name: "CreateArtistForm",
    layout: AdminLayout,
    components:{
        ItemSelector,
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
        const store = useCreateArtistStore();
        const headerStore = useHeaderStore();
        const errors = ref({});
        const t = i18n.global.t;

        const profilePreviewComputed = computed(()=>{
            return store.photo != null ?
                URL.createObjectURL(store.photo) : null;
        });

        const createArtistMethod = ()=>{
            store.showFullScreenLoader = true;
            let categoryIds = getItemIds(store.newData.categories);
            let tagIds = getItemIds(store.newData.tags);

            let data = {
                name: store.newData.name,
                slug: store.newData.slug,
                description: store.newData.description,
                birth_date: store.newData.birth_date,
                photo_id: store.photo ? store.photo.id : null,
                country_id: store.country ? store.country.id : null,
                tags: tagIds,
                categories: categoryIds,
            };

            if (props.isEdit){
                axios.put(`/admin/artists/${props.formerData.id}`, data)
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
                axios.post('/admin/artists', data)
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
                store.country = props.formerData.country;
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
            createArtistMethod,
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
