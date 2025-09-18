<template>
    <section class="bg-[#1e1e2d] w-[265px] z-[50] fixed ltr:left-0 rtl:right-0 bottom-0 top-0 overflow-y-auto">
        <div class="bg-[#1A1A27] h-[65px] items-center grid justify-end px-5">
<!--            <img class="w-[100%] h-[25px]" src="https://preview.keenthemes.com/metronic8/demo1/assets/media/logos/logo-1-dark.svg" alt="">-->
        </div>
        <section class="menu mt-4">
            <div v-for="item in menu">
                <SidebarItem :name="item.name" :svg="item.svg" :link="item.link" :menu="item.submenu" />
            </div>
        </section>
    </section>
</template>

<script>
import SidebarItem from "../../../Pages/Admin/SideBar/SidebarItem.vue";
import { useI18n } from 'vue-i18n'

export default {
    name: "SideBarPanel",
    components: {SidebarItem},
    props: [

    ],
    setup(){
        const { t } = useI18n()

        const menu = [
            {name: t("pages.Dashboard"), svg: 'fi-rr-dashboard', link: '/admin/dashboard/panel', submenu: []},
            {name: t("pages.Users"), svg: 'fi-rr-user', link: '',
                submenu: [
                    {name: t("pages.UsersList"), link: '/admin/users'},
                    {name: t("pages.CreateUser"), link: '/admin/users/create'},
            ]},
            {name: 'هنرمندان', svg: 'fi-rr-user', link: '',
                submenu: [
                    {name: 'نمایش همه هنرمندان', link: '/admin/artists'},
                    {name: 'افزودن هنرمند جدید', link: '/admin/artists/create'},
            ]},
            {name: 'آهنگ ها', svg: 'fi-rr-music', link: '',
                submenu: [
                    {name: 'نمایش همه آهنگ ها', link: '/admin/tracks'},
                    {name: 'افزودن آهنگ جدید', link: '/admin/tracks/create'},
            ]},
            {name: 'آلبوم ها', svg: 'fi-rr-music', link: '',
                submenu: [
                    {name: 'نمایش همه آلبوم ها', link: '/admin/albums'},
                    {name: 'افزودن آلبوم جدید', link: '/admin/albums/create'},
            ]},
            {name: 'پلی لیست ها', svg: 'fi-rr-music', link: '',
                submenu: [
                    {name: 'نمایش همه پلی لیست ها', link: '/admin/playlists'},
                    {name: 'افزودن پلی لیست جدید', link: '/admin/playlists/create'},
            ]},
            // {name: t("pages.Payments"), svg: 'hand-holding-usd', link: '/admin/payments', submenu: []},
            {name: t("pages.ActivityLogs"), svg: 'scroll-old', link: '/admin/activityLogs', submenu: []},
            // {name: t("pages.Views"), svg: 'fi-rr-eye', link: '/admin/views', submenu: []},
            {name: t("pages.DownloadRecords"), svg: 'fi-rr-download', link: '/admin/downloadRecords', submenu: []},
            {name: t("pages.Categories"), svg: 'fi-rr-document', link: '/admin/categories', submenu: []},
            {name: t("pages.Tags"), svg: 'tags', link: '/admin/tags', submenu: []},
            // {name: t("pages.Tickets"), svg: 'tags', link: '/admin/tickets', submenu: []},
            {name: t("pages.Pages"), svg: 'blog-text', link: '',
                submenu: [
                    {name: t("pages.PagesList"), link: '/admin/pages'},
                    {name: t("pages.CreatePage"), link: '/admin/pages/create'},
            ]},
            {name: t("pages.Articles"), svg: 'blog-pencil', link: '',
                submenu: [
                    {name: t("pages.ArticlesList"), link: '/admin/articles'},
                    {name: t("pages.CreateArticle"), link: '/admin/articles/create'},
            ]},
            {name: t("pages.FileManager"), svg: 'file-invoice', link: '/admin/fileManager', submenu: []},
            {name: t("pages.Gallery"), svg: 'fi-rr-gallery', link: '/admin/gallery', submenu: []},
            {name: t("pages.PermissionRoles"), svg: 'permission-role', link: '',
                submenu: [
                    {name: t("pages.Roles"), link: '/admin/roles'},
                    {name: t("pages.Permissions"), link: '/admin/permissions'},
            ]},
            {name: t("pages.Settings"), svg: 'fi-rr-settings', link: '',
                submenu: [
                    {name: t("pages.GeneralSettings"), link: '/admin/settings/general'},
                    {name: t("pages.SEOSettings"), link: '/admin/settings/seo'},
                    {name: t("pages.MenusSettings"), link: '/admin/settings/menu'},
                    // {name: t("pages.ThemeSettings"), link: '/admin/settings/theme'},
            ]},
            {name: t("pages.Documents"), svg: 'fi-rr-document', link: '/admin/documents', submenu: []},
        ];


        return {
            menu,
        }
    },

}
</script>

<style scoped>

</style>
