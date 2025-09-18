import { defineStore } from 'pinia'

import i18n from '../../../i18n';
import iziToast from "izitoast";
const t = i18n.global.t;

export const useMenuSettingsStore = defineStore('MenuSettings', {
    state: () => {
        return {
            // constants start
            PageTitle: `${t('pages.MenusSettings')} Settings`,
            // constants end
            // main data
            menus: {},
            newMenus: {},

            // Rows
            openedMenuId: 0,
            openedSubmenu: null,
            checkedItem: {
                htmlElement: null,
                item: null,
            },

            // Modal
            // Menu modal
            showCreateMenuModal: false,
            editMenu: {
                isEdit: false,
                toBeEdited: {},
            },
            // Submenu modal
            showCreateSubmenuModal: false,
            editSubmenu:{
                isEdit: false,
                toBeEdited: {},
            },
            // loader
            showFullScreenLoader: false,
        }
    },
    actions: {
        // main data
        setMenusData(data){
            this.menus = JSON.parse(JSON.stringify(data));
        },
        resetChanges(){
            this.newMenus = JSON.parse(JSON.stringify(this.menus));
        },
        updateMenusData(){
            axios.post('/admin/menus/indexDataApi')
                .then((res)=>{
                    this.setMenusData(res.data);
                    this.resetChanges();
                })
        },

        // Rows
        openMenu(menu){
            this.openedMenuId = this.openedMenuId === menu.id ? 0 : menu.id;
        },
        openSubmenu(item, parent = null){
            if (parent)
                this.openedSubmenu = item === this.openedSubmenu ? parent : item;
            else
                this.openedSubmenu = item === this.openedSubmenu ? null : item;
        },
        isSubmenuOpened(item){
            if (this.openedSubmenu === item)
                return true;

            // return true if a submenu in item.submenus is opened(the parent needs to be open to work with the child)
            return item.submenus.some(child => this.openedSubmenu === child);
        },
        setCheckedItem(e, item){
            // decide the right value for clicked checkbox.
            if (item === this.checkedItem.item) {
                this.checkedItem.item = null;
                e.target.checked = false;
            }
            else {
                // unchecks the former html input element
                if (this.checkedItem.htmlElement)
                    this.checkedItem.htmlElement.checked = false;
                // sets the new values
                this.checkedItem.item = item;
                this.checkedItem.htmlElement = e.target;
                e.target.checked = true;
            }
        },
        isSubmenuChecked(item){
            return this.checkedItem.item === item;
        },
        deleteSubmenu(menuArr, submenuIndex){
            if (this.isSubmenuChecked(menuArr[submenuIndex])){
                this.checkedItem.htmlElement.checked = false;
                this.checkedItem.item = null;
            }

            menuArr.splice(submenuIndex, 1);
        },
        resetSubmenusButton(menuIndex){
            let newMenus = Object.values(this.newMenus);
            let oldMenus = Object.values(this.menus);

            newMenus[menuIndex] = JSON.parse(JSON.stringify(oldMenus[menuIndex]));

            this.newMenus = newMenus;
        },

        // Modal
        // Menu modal
        closeMenuModal(){
            this.showCreateMenuModal = false;
        },
        openMenuModalCreate(){
            this.showCreateMenuModal = true;
            this.editMenu.isEdit = false;
        },
        openMenuModalEdit(item){
            this.showCreateMenuModal = true;
            this.editMenu.isEdit = true;
            this.editMenu.toBeEdited = JSON.parse(JSON.stringify(item));
        },
        // Submenu modal
        closeSubmenuModal(){
            this.showCreateSubmenuModal = false;
        },
        openSubmenuModalCreate(){
            this.showCreateSubmenuModal = true;
            this.editSubmenu.isEdit = false;
        },
        openSubmenuModalEdit(item){
            this.showCreateSubmenuModal = true;
            this.editSubmenu = {
                isEdit : true,
                toBeEdited : item,
            }
        },

        // finalizing data
        addSubmenuItem(newItem){
            if (this.checkedItem.item){
                if (!this.checkedItem.item.submenus)
                    this.checkedItem.item.submenus = [];

                this.checkedItem.item.submenus.push(newItem);
            }
        },
        addTagSubmenuSuggestion(tag){
            let url = `/tags/${tag.slug}`;

            let newItem = {title: tag.name, url: url, submenus: []};
            this.addSubmenuItem(newItem);
        },
        addCategorySubmenuSuggestion(category){
            let url = `/categories/${category.slug}`;

            let newItem = {title: category.name, url: url, submenus: []};
            this.addSubmenuItem(newItem);
        },

        // below is for deleting
        askDeleteAffirmation(data){
            iziToast.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: 'توجه!',
                message: 'آیا مطمئن هستید؟',
                position: 'center',
                buttons: [
                    ['<button><b>بله</b></button>', (instance, toast) => {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                        this.deleteRowFinalize(data);
                    }, true],
                    ['<button>خیر</button>', function (instance, toast) {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    }],
                ],
            });
        },
        deleteRowFinalize(rowData){
            axios.delete(`/admin/menus/${rowData.id}`)
                .then(res => {
                    if (res.status === 200){
                        this.updateMenusData();
                        iziToast.success({title: t('toasts.success'), message: t('toasts.deletedSuccessfully'),});
                    }
                });
        },

        // sending menu's submenu data
        sendMenuData(menu, menuIndex){
            let menus = Object.values(this.newMenus);
            let submenus = menus[menuIndex].submenus;
            this.showFullScreenLoader = true;

            axios.post('/admin/menus/updateMenuSubmenu', {
                'id': menu.id,
                'submenus': submenus,
            }).then((res) => {
                this.showFullScreenLoader = false;

                if (res.status === 200){
                    this.updateMenusData();
                    iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});
                }
            }).catch((error)=>{
                this.showFullScreenLoader = false;
                iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
            });
        },

    },
})
