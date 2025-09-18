import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useDownloadRecordsStore = defineStore('DownloadRecords', {
    state: () => {
        return {
            // constants start
            PageTitle: t('pages.DownloadRecords'),
            tableLayout: {
                gridTemplateColumns: '3rem 4rem 1fr 1fr 10rem 6rem',
                labels: [
                    t('fields.ID'),
                    t('fields.downloader'),
                    t('fields.product'),
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
