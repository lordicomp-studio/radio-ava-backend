<template>
    <div class="filePicker relative">
        <input
            :value="modelValue"
            @keyup="updateModelValueFromInputMethod($event)"
            type="text"
            class="input-text w-[100%] bg-gray-100"
            :placeholder="placeholder"
        >

        <div class="absolute top-0 bottom-0 ltr:right-0 rtl:left-0 grid grid-cols-[auto,auto]
                items-center gap-2 bg-gray-200 rtl:rounded-l-lg ltr:rounded-r-lg m-[1px] px-1">
            <a
                :href="`/admin/fileManager/getFile?path=${startFromPath}/${modelValue}`"
                class="h-[100%] px-1.5 cursor-pointer group flex items-center"
            >
                <SvgComponent name="fi-rr-download" size="1.1rem" color="#aaa"
                              class="group-hover:fill-[#009ef7] transition-colors duration-200"/>
            </a>
            <div
                @click="openModalMethod"
                class="h-[100%] px-1.5 cursor-pointer group flex items-center"
            >
                <SvgComponent name="pencil" size="1.1rem" color="#aaa"
                              class="group-hover:fill-[#009ef7] transition-colors duration-200"/>
            </div>
        </div>
    </div>

    <SelectFileModal
        :is-active="showSelectFileModal"
        :click-outside-method="closeModalMethod"
        :close-btn-method="closeModalMethod"
        :receive-file-method="receiveFileMethod"
        :remove-file-method="removeFileMethod"
    />
</template>

<script>
import SvgComponent from "@/Components/admin/Widgets/SvgComponent.vue";
import {ref, watch} from "vue";
import SelectFileModal from "@/Components/admin/Widgets/SelectFileModal.vue";
import i18n from '../../../../i18n';
const t = i18n.global.t;

export default {
    name: 'FilePicker',
    components: {SelectFileModal, SvgComponent},
    props:{
        modelValue: String,
        placeholder: {
            type: String,
            default: t('forms.filePicker.selectFile'),
        },
        startFromPath: {
            type: String,
            default: '',
        }
    },
    setup(props, context){
        const showSelectFileModal = ref(false);

        const updateModelValueFromInputMethod = (e)=>{
            context.emit('update:modelValue', e.target.value);
        }

        const openModalMethod = ()=>{
            showSelectFileModal.value = true;
        };

        const closeModalMethod = ()=>{
            showSelectFileModal.value = false;
        };

        const receiveFileMethod = (item)=>{
            let path = item.path.substring(props.startFromPath.length)

            context.emit('update:modelValue', path);
            closeModalMethod();
        };

        const removeFileMethod = ()=>{
            context.emit('update:modelValue', '');
            closeModalMethod();
        };

        return{
            updateModelValueFromInputMethod,
            showSelectFileModal,
            openModalMethod,
            closeModalMethod,
            receiveFileMethod,
            removeFileMethod,
        }
    },
}

</script>

<style scoped>

</style>
