<template>
    <div class="mediumPicker">
        <div class="bg-white rounded-lg p-4">
            <h3 class="text-xl text-black font-medium capitalize">{{ $t('forms.mediumPicker.header', {title: title}) }}</h3>

            <div class="file-input cursor-pointer" @click="openModalMethod">
                <div class="shadow-lg w-[70%] grid items-center justify-center h-[12rem] m-auto rounded-lg border-3 border-[#eee] relative mt-8">
                    <figure v-if="modelValue" class="w-[100%] h-[100%] overflow-hidden">
                        <img :src="`/storage${modelValue.url}`" class="object-contain w-[100%] h-[100%]" alt="">
                    </figure>
                    <i v-else class="grid justify-center">
                        <SvgComponent name="fi-rr-picture" size="4rem" color="#DCE5F1" />
                    </i>
                    <div class="absolute bg-white top-[-.5rem] shadow-md rounded-full p-2 right-[-.5rem]">
                        <SvgComponent name="fi-rr-pencil" size="1rem" color="#a1a5b7" />
                    </div>
                </div>
            </div>

            <p class="text-xs mt-2 text-[#a1a5b7] font-light text-center">{{ $t('forms.mediumPicker.comment', {title: title}) }}</p>
        </div>

        <SelectMediumModal
            :is-active="showSelectMediumModal"
            :click-outside-method="closeModalMethod"
            :close-btn-method="closeModalMethod"
            :receive-medium-method="receiveMediumMethod"
            :remove-photo-method="removePhotoMethod"
        />
    </div>
</template>

<script>
import SvgComponent from "../SvgComponent.vue";
import SelectMediumModal from "../SelectMediumModal.vue";
import {ref} from "vue";

export default {
    name: "MediumPicker",
    components: {SelectMediumModal, SvgComponent},
    props: {
        modelValue:{
            type: Object
        },
        title: {
            type: String,
            default: '',
        },
    },
    setup(props, context){
        const showSelectMediumModal = ref(false);

        const openModalMethod = ()=>{
            showSelectMediumModal.value = true;
        };

        const closeModalMethod = ()=>{
            showSelectMediumModal.value = false;
        };

        const receiveMediumMethod = (medium)=>{
            context.emit('update:modelValue', JSON.parse(JSON.stringify(medium)));
            closeModalMethod();
        };

        const removePhotoMethod = ()=>{
            context.emit('update:modelValue', null);
            closeModalMethod();
        };

        return {
            showSelectMediumModal,
            openModalMethod,
            closeModalMethod,
            receiveMediumMethod,
            removePhotoMethod,
        }
    },
}
</script>

<style scoped>

</style>
