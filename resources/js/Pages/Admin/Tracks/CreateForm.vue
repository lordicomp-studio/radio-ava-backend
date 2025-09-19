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
                    <SimpleTextInput
                        :isEssential="true"
                        label="طول"
                        errorKeyName="duration"
                        v-model="store.newData.duration"
                        :errors="errors"
                    />
                </div>

                <div class="w-[500px] mt-4 text-start">
                    <h4 class="text-sm font-medium mb-1 text-[#3f4254]">آلبوم</h4>
                    <ItemSelector v-model="store.newData.album" searchLink="/admin/albums/indexDataApi"/>
                    <p v-if="errors['album_id']" class="text-danger">{{ errors['album_id'][0] }}</p>
                </div>

                <div class="w-[500px] mt-4 text-start">
                    <h4 class="text-sm font-medium mb-1 text-[#3f4254]">هنرمند</h4>
                    <ItemSelector v-model="store.newData.artist" searchLink="/admin/artists/indexDataApi"/>
                    <p v-if="errors['artist_id']" class="text-danger">{{ errors['artist_id'][0] }}</p>
                </div>

                <div class="mt-7 grid grid-cols-[auto,auto] justify-start items-center gap-2">
                    <input
                        v-model="store.newData.is_mv"
                        type="checkbox"
                        class="input-check inline-block"
                    >
                    <h4 class="text-sm text-[#3f4254]">
                        موزیک ویدیو است؟
                    </h4>
                    <p v-if="errors['is_mv']" class="text-xs mt-1 text-danger font-light">{{ errors.is_mv[0] }}</p>
                </div>

                <div class="mt-7">
                    <SimpleTextInput
                        :isEssential="false"
                        label="لینک فایل لیریکس"
                        errorKeyName="lyrics_file_url"
                        v-model="store.newData.lyrics_file_url"
                        :errors="errors"
                    />
                </div>

                <div class="mt-7">
                    <h4 class="text-sm mb-2 text-[#3f4254]">
                        تاریخ انتشار
                    </h4>
                    <DatePicker v-model="store.newData.release_date"/>
                    <p v-if="errors['release_date']" class="text-xs text-danger font-light">{{ errors.release_date[0] }}</p>
                </div>

            </div>

            <div class="mt-4 bg-white rounded-lg p-4">
                <TrackFilesHandler :store="store"/>
            </div>

        </div>

        <div class="left space-y-4">
            <MediumPicker title="آهنگ" v-model="store.photo" />

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
import TagCategorySelector from "../../../Components/admin/Widgets/TagCategorySelector.vue";
import CategoryCreateModal from "../../../Components/admin/Categories/CreateModal.vue"
import TagCreateModal from "../../../Components/admin/Tags/CreateModal.vue";
import {useCreateTrackStore} from "../../../Stores/admin/Tracks/createStore";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import {useCategoryIndexStore} from "../../../Stores/admin/Categories/CategoriesIndexStore";
import {useTagIndexStore} from "@/Stores/admin/Tags/TagsIndexStore";
import SelectBasic from "../../../Components/admin/Widgets/Input/Selects/SelectBasic.vue";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import MediumPicker from "@/Components/admin/Widgets/Input/MediumPicker.vue";
import ResizableTextArea from "../../../Components/admin/Widgets/Input/Texts/ResizableTextArea.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import DatePicker from "vue3-persian-datetime-picker";
import i18n from '../../../i18n';
import ItemSelector from "@/Components/admin/Widgets/Input/ItemSelector.vue";
import TrackFilesHandler from "@/Components/admin/Tracks/TrackFilesHandler.vue";

export default {
    name: "CreateTrackForm",
    layout: AdminLayout,
    components:{
        TrackFilesHandler,
        ItemSelector,
        DatePicker,
        SimpleTextInput,
        ResizableTextArea,
        MediumPicker,
        Loader,
        SelectBasic, TagCategorySelector, SelectMediumModal,
        SvgComponent, CategoryCreateModal, TagCreateModal},
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
        const store = useCreateTrackStore();
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
                name: store.newData.name,
                slug: store.newData.slug,
                duration: store.newData.duration,
                is_mv: store.newData.is_mv,
                is_published: store.newData.is_published,
                lyrics_file_url: store.newData.lyrics_file_url,
                release_date: store.newData.release_date,
                cover_id: store.photo?.id,
                artist_id: store.newData.artist?.id,
                tags: tagIds,
                categories: categoryIds,
                track_files: store.newData.track_files,
                // status: store.newData.status,
            };

            if (props.isEdit){
                axios.put(`/admin/tracks/${props.formerData.id}`, data)
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
                axios.post('/admin/tracks', data)
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
                store.newData.is_mv = Boolean(store.newData.is_mv);
                store.photo = props.formerData.cover;
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

</style>
