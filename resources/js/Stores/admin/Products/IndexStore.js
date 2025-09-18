import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useProductsStore = defineStore('Products', {
    state: () => {
        return {
            // constants start
            PageTitleProducts: t('pages.ProductsList'),
            PageTitleProductEditRequests: t('pages.ProductEditRequests'),
            tableLayout: {
                gridTemplateColumns: '3rem 3rem 4rem 2fr 2fr repeat(3,.8fr) 6rem',
                labels: [
                    t('fields.ID'),
                    t('fields.cover'),
                    t('fields.name'),
                    t('fields.creator'),
                    t('fields.status'),
                    t('fields.price'),
                    t('fields.createdAt'),
                    t('fields.actions'),
                ],
            },
            // constants end
            tableData: {},
            type: '',
            // below is for selecting multiple data
            selectedIds: [],
            areAllSelected: false,
            isSearching: false,
        }
    },
    actions: {
        setTableData(data){
            this.tableData = JSON.parse(JSON.stringify(data));
        },
        updateTableData(){
            axios.post(`/admin/${this.type}s/indexDataApi`)
                .then((res)=>{
                    this.tableData = res.data;
                })
        },
        selectAll(){
            if (this.tableData.data.length === this.selectedIds.length){
                this.selectedIds.splice(0, this.selectedIds.length);
            }else{
                // it empties the array
                this.selectedIds.splice(0, this.selectedIds.length);
                // then pushes all the values
                this.tableData.data.forEach( (item) => {
                    this.selectedIds.push(item.id);
                });
            }
        },
        checkIfAllAreSelected(){
            this.areAllSelected = this.selectedIds.length === this.tableData.data.length;
        }
    },
})
