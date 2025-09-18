<template>
    <Head>
        <title>{{ store.title }}</title>
    </Head>

    <section class="ps-[265px] bg-white fixed right-0 left-0 top-0 z-50 shadow">
        <div class="flex justify-between p-3 px-8 border-b-[1px] border-[#eff2f5]">
            <div class="flex justify-end items-center">
                <span class="text-base font-semibold text-[#000] cursor-auto">{{ store.title }}</span>
            </div>
            <div class="flex justify-end group relative">
                <div class="w-[40px] h-[40px] rounded-lg overflow-hidden">
                    <img
                        :src="userAuth.profile_photo ? `/storage${userAuth.profile_photo.url}` : '/Images/profile default.png'"
                        class="w-[100%] h-[100%] object-cover"
                        alt=""
                    >
                </div>
                <div
                    class="userInfo absolute top-[90%] bg-white p-6 rounded-lg
                       invisible group-hover:visible hover:visible shadow-[0_0_50px_0_rgba(82,63,105,0.15)]
                       opacity-0 group-hover:opacity-100 hover:opacity-100
                       transition-all duration-300 group-hover:top-[78%] hover:top-[78%]"
                >
                    <div class="grid grid-cols-[auto,auto] justify-start gap-2 pb-2 border-b border-[#ccc]">
                        <figure class="h-[50px] w-[50px] rounded-full overflow-hidden">
                            <img
                                :src="userAuth.profile_photo ? `/storage${userAuth.profile_photo.url}` : '/Images/profile default.png'"
                                class="object-cover w-[100%] h-[100%]" alt=""
                            >
                        </figure>
                        <div class="info grid grid-cols-[auto] items-center">
                            <span class="text-gray-900 transition-colors duration-300">{{userAuth.name}}</span>
                            <span class="text-gray-500">{{userAuth.email}}</span>
                        </div>
                    </div>
                    <div class="content pt-3">
                        <div class="buttons">
                            <button @click="logoutMethod" class="btn btn-md btn-light-danger btn-light-active-danger">{{$t('auth.logout')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import {Head, usePage} from '@inertiajs/vue3';
import {onMounted} from "vue";
import {router} from '@inertiajs/vue3'; // this is used for setting the title of web page(the <Head> tag).

export default {
    name: "HeaderPanel",
    components: { Head },
    setup(props){
        const store = useHeaderStore();
        const userAuth = usePage().props.user_auth;


        const logoutMethod = () => {
            router.post(route('auth.logout'));
        };

        return {
            store,
            userAuth,
            logoutMethod,
        }
    },
}
</script>

<style scoped>

</style>
