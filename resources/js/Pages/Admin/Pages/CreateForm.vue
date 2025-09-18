<template>
    <div class="allCreateForm bg-light grid grid-cols-[1fr,20rem] gap-8 p-4">
        <div
            v-if="store.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[101]"
        >
            <Loader class="rounded-lg" :isActive="store.showFullScreenLoader"/>
        </div>

        <div class="right">
            <div class="advance">
                <div class="bg-white rounded-lg p-4">
                    <div class="">
                        <SimpleTextInput
                            :isEssential="false"
                            :label="$t('forms.common.title')"
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
                </div>
            </div>
            <div class="grid grid-cols-[auto,auto] gap-4 mt-8 justify-start">
                <button class="btn btn-primary btn-active-primary" @click="createPageMethod">{{$t('forms.common.save')}}</button>
            </div>
        </div>

        <div class="left">
            <MediumPicker :title="$t('forms.pages.page')" v-model="store.photo" />
        </div>

        <div class="textEditor col-span-2">
            <h4 class="text-sm mb-2 text-[#3f4254]">
                {{ $t('forms.common.description') }}
            </h4>
            <p v-if="errors['description']" class="text-xs mt-2 text-danger font-light">{{ errors.description[0] }}</p>
            <DocumentTextEditor v-model="store.newData.description"/>
        </div>

        <SelectMediumModal
            :is-active="store.showSelectMediumModal"
            :click-outside-method="store.closeModal"
            :close-btn-method="store.closeModal"
            :receive-medium-method="store.receiveMedium"
            :remove-photo-method="store.removePhoto"
        />
    </div>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {computed, onBeforeMount, onMounted, ref} from 'vue';
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import {useCreatePageStore} from "../../../Stores/admin/Pages/createStore";
import SelectMediumModal from "../../../Components/admin/Widgets/SelectMediumModal.vue";
import iziToast from "izitoast";
import DocumentTextEditor from "../../../Components/admin/Widgets/Input/Texts/DocumentTextEditor.vue";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import MediumPicker from "@/Components/admin/Widgets/Input/MediumPicker.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    name: "CreatePageForm",
    layout: AdminLayout,
    components:{SimpleTextInput, MediumPicker, Loader, DocumentTextEditor, SelectMediumModal, SvgComponent},
    props:{
        roles:{
            type: Array,
        },
        isEdit: {
            type: Boolean,
            Default: false,
        },
        formerData: {
            type: Object,
            Default: null,
        },
    },
    setup(props){
        const store = useCreatePageStore();
        const headerStore = useHeaderStore();
        const errors = ref({});

        const profilePreviewComputed = computed(()=>{
            return store.photo != null ?
                URL.createObjectURL(store.photo) : null;
        });

        const createPageMethod = ()=>{
            store.showFullScreenLoader = true;

            if (props.isEdit){
                axios.put(`/admin/pages/${props.formerData.id}`, {
                    name: store.newData.name,
                    slug: store.newData.slug,
                    photoId: store.photo ? store.photo.id : null,
                    description: store.newData.description,
                }).then(res=>{
                    store.showFullScreenLoader = false;
                    if (res.status === 200){
                        // store.resetFormValues();
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
                axios.post('/admin/pages', {
                    name: store.newData.name,
                    slug: store.newData.slug,
                    photoId: store.photo ? store.photo.id : null,
                    description: store.newData.description,
                }).then(res=>{
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
            createPageMethod,
        }
    }
}

</script>
