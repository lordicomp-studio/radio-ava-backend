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
                        v-model="store.newData.title"
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
            </div>

            <div class="textEditor w-[99%] mt-4">
                <h4 class="text-sm mb-2 text-[#3f4254]">
                    {{ $t('forms.common.text') }}
                </h4>
                <p v-if="errors['body']" class="text-xs mt-2 text-danger font-light">{{ errors.body[0] }}</p>
                <DocumentTextEditor v-model="store.newData.body"/>
            </div>
        </div>

        <div class="left space-y-4">
            <MediumPicker :title="$t('forms.articles.article')" v-model="store.photo" />

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
                <div class="grid grid-cols-[auto] items-start gap-y-2">
                    <h4 class="text-lg text-black inline-block">
                        {{ $t('forms.publishTimeHandler.publishTime') }}
                    </h4>

                    <div class="grid grid-cols-[auto] gap-2">
                        <div class="grid grid-cols-[auto,auto] justify-start gap-2">
                            <h4 class="text-sm text-black font-medium inline-block">
                                {{ $t('forms.publishTimeHandler.realPublishTime') }}
                            </h4>
                            <input v-model="useRealDate" type="checkbox" class="input-check inline-block">
                        </div>

                        <DatePicker v-model="store.newData.published_at"
                                    :disabled="useRealDate" :class="{'disabledDatepicker' : useRealDate}"/>
                        <p v-if="errors['published_at']" class="text-xs text-danger font-light">{{ errors.published_at[0] }}</p>
                        <p v-else class="text-xs text-[#a1a5b7] font-light">{{ $t('forms.publishTimeHandler.publishTimeComment') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-[1fr,auto] gap-[3.8rem] mt-4">
                    <SelectBasic
                        v-model="store.newData.status"
                        :options-array="{
                            0: $t('forms.articles.draft'),
                            1: $t('forms.articles.published'),
                        }"
                        :showSearch="false"
                    />

                    <button class="btn btn-primary btn-active-primary" @click="createArticleMethod">
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
import {useCreateArticleStore} from "../../../Stores/admin/Articles/createStore";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import {useCategoryIndexStore} from "../../../Stores/admin/Categories/CategoriesIndexStore";
import {useTagIndexStore} from "@/Stores/admin/Tags/TagsIndexStore";
import SelectBasic from "../../../Components/admin/Widgets/Input/Selects/SelectBasic.vue";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import MediumPicker from "@/Components/admin/Widgets/Input/MediumPicker.vue";
import ResizableTextArea from "../../../Components/admin/Widgets/Input/Texts/ResizableTextArea.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import i18n from '../../../i18n';

export default {
    name: "CreateArticleForm",
    layout: AdminLayout,
    components:{
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
        const store = useCreateArticleStore();
        const headerStore = useHeaderStore();
        const errors = ref({});
        const t = i18n.global.t;

        const profilePreviewComputed = computed(()=>{
            return store.photo != null ?
                URL.createObjectURL(store.photo) : null;
        });

        const createArticleMethod = ()=>{
            store.showFullScreenLoader = true;
            let categoryIds = getItemIds(store.newData.categories);
            let tagIds = getItemIds(store.newData.tags);

            let data = {
                title: store.newData.title,
                slug: store.newData.slug,
                description: store.newData.description,
                body: store.newData.body,
                published_at: store.newData.published_at,
                useRealDate: useRealDate.value,
                photoId: store.photo ? store.photo.id : null,
                tags: tagIds,
                categories: categoryIds,
                status: store.newData.status,
            };

            if (props.isEdit){
                axios.put(`/admin/articles/${props.formerData.id}`, data)
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
                axios.post('/admin/articles', data)
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
            createArticleMethod,
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
