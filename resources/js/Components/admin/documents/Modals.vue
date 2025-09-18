<template>
    <section class="modals p-4">
        <div class="flex justify-start space-x-reverse space-x-4">
            <button class="btn btn-primary" @click="showSimpleModal = true">show simple modal</button>
            <button class="btn btn-primary ml-4" @click="showCustomModal = true">show custom modal</button>
        </div>
        <SimpleModal
            title="test title"
            body="this is a test body. this is a test body. this is a test body this is a test body"
            button-accept-text="آره"
            button-cancel-text="نه"
            :is-active="showSimpleModal"
            @accept="showSimpleModal = false; testLogMethod('SimpleModal accept');"
            @cancel="showSimpleModal = false; testLogMethod('SimpleModal cancel');"
            @clickOutside="showSimpleModal = false;"
        />
        <CustomModal
            :is-active="showCustomModal"
            @clickOutside="showCustomModal = false;testLogMethod('CustomModal click outside');"
        >
            <template v-slot:title>
                <div class="grid grid-cols-[auto,auto] justify-start items-center gap-4">
                    <SvgComponent name="fi-rr-alarm-clock" size="1.1rem" color="#aaa"/>
                    تیتر تستی
                </div>
            </template>
            <template v-slot:body>
                <div class="w-[60rem]">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aspernatur deserunt, doloribus illo inventore maiores nam officiis
                    reiciendis unde voluptatem? A, ducimus labore! Accusantium cumque deleniti magni nulla officia repellat.
                    <h3>aksjdkasjd</h3>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aspernatur deserunt, doloribus illo inventore maiores nam officiis
                    reiciendis unde voluptatem? A, ducimus labore! Accusantium cumque deleniti magni nulla officia repellat.
                    <button class="btn btn-primary btn-active-primary mb-2 mx-auto ml-4">
                        <svg-component name="fi-rr-alarm-clock" size="1.1rem" color="#ccc"></svg-component>
                        دکمه تستی
                    </button>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aspernatur deserunt, doloribus illo inventore maiores nam officiis
                    reiciendis unde voluptatem? A, ducimus labore! Accusantium cumque deleniti magni nulla officia repellat.
                </div>
            </template>
            <template v-slot:actions>
                <div class="grid grid-cols-[auto,auto,auto] justify-center gap-4">
                    <button class="btn btn-primary btn-active-primary">
                        <svg-component name="fi-rr-alarm-clock" size="1.1rem" color="#ccc"></svg-component>
                        Base
                    </button>
                    <button class="btn btn-light-success btn-light-active-success">
                        <svg-component name="fi-rr-alarm-clock" size="1.1rem" color="#ccc"></svg-component>
                        light
                    </button>
                    <button class="btn btn-outline btn-outline-info">
                        <svg-component name="fi-rr-alarm-clock" size="1.1rem" color="#ccc"></svg-component>
                        Outline
                    </button>
                </div>
            </template>
        </CustomModal>
    </section>
</template>

<script>
import SimpleModal from "../Widgets/Modal/SimpleModal.vue";
import CustomModal from "../Widgets/Modal/CustomModal.vue";
import SvgComponent from "../Widgets/SvgComponent.vue";
import {ref} from "vue";

export default {
    name: "Modals",
    components: {SvgComponent, SimpleModal, CustomModal},
    setup(){
        const showSimpleModal = ref(false);
        const showCustomModal = ref(false);

        const testLogMethod = (data)=>{
            console.log(data);
        }

        return{
            showSimpleModal,
            showCustomModal,
            testLogMethod,
        }
    }
}
</script>

<style scoped>

</style>
