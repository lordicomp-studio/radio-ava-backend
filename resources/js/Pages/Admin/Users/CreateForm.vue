<template>
    <div class="allCreateForm bg-light grid grid-cols-[1fr,20rem] gap-8 p-4">
        <div
            v-if="createUserStore.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[101]"
        >
            <Loader class="rounded-lg" :isActive="createUserStore.showFullScreenLoader"/>
        </div>

        <div class="right">
            <div class="advance">
                <div class="bg-white rounded-lg p-4">
                    <div class="">
                        <SimpleTextInput
                            :isEssential="true"
                            :label="$t('forms.users.name')"
                            errorKeyName="name"
                            v-model="createUserStore.newUser.name"
                            :errors="errors"
                        />
                    </div>

                    <div class="mt-7">
                        <SimpleTextInput
                            :label="$t('forms.users.fname')"
                            errorKeyName="first_name"
                            v-model="createUserStore.newUser.first_name"
                            :errors="errors"
                        />
                    </div>

                    <div class="mt-7">
                        <SimpleTextInput
                            :label="$t('forms.users.lname')"
                            errorKeyName="last_name"
                            v-model="createUserStore.newUser.last_name"
                            :errors="errors"
                        />
                    </div>

                    <div class="mt-7">
                        <SimpleTextInput
                            :isEssential="true"
                            :label="$t('forms.users.email')"
                            errorKeyName="email"
                            v-model="createUserStore.newUser.email"
                            :errors="errors"
                        />
                    </div>

                    <div class="mt-7">
                        <SimpleTextInput
                            :isEssential="true"
                            :label="$t('forms.users.password')"
                            errorKeyName="password"
                            v-model="createUserStore.newUser.password"
                            :errors="errors"
                        />
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-[auto,auto] gap-4 mt-8 justify-start">
                <button class="btn btn-primary btn-active-primary" @click="createUserMethod">{{ $t('forms.common.save') }}</button>
            </div>
        </div>
        <div class="left">
            <MediumPicker :title="$t('forms.users.user')" v-model="createUserStore.profilePhoto" />

            <div class="bg-white rounded-lg p-8 mt-8">
                <h3 class="text-xl text-black font-medium flex justify-between items-center capitalize">{{ $t('forms.users.level') }}</h3>
                <div class="mt-7">
                    <SelectBasic :options-array="levels" v-model="createUserStore.newUser.level" :showSearch="false" />
                </div>
                <p class="text-xs text-[#a1a5b7] font-light text-center mt-4">{{ $t('forms.users.levelComment') }}</p>
            </div>

            <div class="bg-white rounded-lg p-8 mt-8">
                <h3 class="text-xl text-black font-medium flex justify-between items-center capitalize">{{$t('forms.users.role')}}</h3>
                <div class="mt-7">
                    <SelectBasic :options-array="allRolesComputed" v-model="createUserStore.roleName" />
                </div>
                <p class="text-xs text-[#a1a5b7] font-light text-center mt-4">{{ $t('forms.users.roleComment') }}</p>
            </div>

        </div>
    </div>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {computed, onBeforeMount, onMounted, ref} from 'vue';
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import SelectBasic from "../../../Components/admin/Widgets/Input/Selects/SelectBasic.vue";
import {useCreateUserStore} from "../../../Stores/admin/Users/createStore";
import SelectMediumModal from "../../../Components/admin/Widgets/SelectMediumModal.vue";
import iziToast from "izitoast";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import MediumPicker from "@/Components/admin/Widgets/Input/MediumPicker.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    name: "CreateForm",
    layout: AdminLayout,
    components:{SimpleTextInput, MediumPicker, Loader, SelectMediumModal, SelectBasic, SvgComponent},
    props:{
        roles:{
            type: Array,
        },
        levels:{
            type: Object,
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
        const createUserStore = useCreateUserStore();
        const headerStore = useHeaderStore();

        const errors = ref({});

        const profilePreviewComputed = computed(()=>{
            return createUserStore.profilePhoto != null ?
                URL.createObjectURL(createUserStore.profilePhoto) : null;
        });

        const createUserMethod = ()=>{
            createUserStore.showFullScreenLoader = true;

            let data = {
                name: createUserStore.newUser.name,
                first_name: createUserStore.newUser.first_name,
                last_name: createUserStore.newUser.last_name,
                email: createUserStore.newUser.email,
                password: createUserStore.newUser.password,
                profilePhotoId: createUserStore.profilePhoto ? createUserStore.profilePhoto.id : null,
                roleName: createUserStore.roleName,
                level: createUserStore.newUser.level,
            };

            if (props.isEdit){
                axios.put(`/admin/users/${props.formerData.id}`, data)
                    .then(res=>{
                        createUserStore.showFullScreenLoader = false;
                        if (res.status === 200){
                            // createUserStore.resetFormValues();
                            errors.value = {};
                            iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});
                        }
                    }).catch((_error) => {
                        createUserStore.showFullScreenLoader = false;
                        errors.value = _error.response.data.errors;
                        iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                    });
            }
            else {
                axios.post('/admin/users', data)
                    .then(res=>{
                        if (res.status === 200){
                            createUserStore.showFullScreenLoader = false;
                            createUserStore.resetFormValues();
                            errors.value = {};
                            iziToast.success({title: t('toasts.success'), message: t('toasts.createdSuccessfully'),});
                        }
                    }).catch((_error) => {
                        createUserStore.showFullScreenLoader = false;
                        errors.value = _error.response.data.errors;
                        iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                    });
            }
        };

        const allRolesComputed = computed(()=>{
            let output = {};

            output.normalUser = t('forms.users.normalUser');
            for (const itemKey in props.roles) {
                output[props.roles[itemKey]] = props.roles[itemKey];
            }

            return output;
        });

        onBeforeMount(()=>{
            headerStore.title = props.isEdit ? createUserStore.PageTitleEdit : createUserStore.PageTitleCreate;
        })

        onMounted(()=>{
            // pass values to store
            createUserStore.allLevels = props.levels;

            if (props.isEdit){
                createUserStore.newUser = props.formerData;
                createUserStore.roleName = props.formerData?.roles[0]?.name ?? 'normalUser';
                createUserStore.profilePhoto = props.formerData.profile_photo;
            }else {
                createUserStore.resetFormValues();
            }
        });

        return{
            createUserStore,
            errors,
            profilePreviewComputed,
            createUserMethod,
            allRolesComputed,
        }
    }
}

</script>
