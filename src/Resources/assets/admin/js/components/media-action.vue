<template>
    <div class="media-actions px-2  py-3 bg-light border rounded d-flex flex-gap-2 align-items-center flex-wrap">
        <a v-if="!bulkSelection" href="" class="arrange arrange-list bi bi-list-ul border border-info "
           @click="listView"></a>
        <a v-if="!bulkSelection" href="" class="active arrange arrange-grid bi bi-grid border border-info  m-minus-l-1"
           @click="thumbView"></a>
        <select v-if="!bulkSelection" name="" class="m-control" id="" @change="filterMedia">
            <option value="all">All Media</option>
            <option value="image">Images</option>
            <option value="audio">Audio</option>
            <option value="video">Video</option>
            <option value="document">Documents</option>
            <option value="spreadsheet">Spreadsheets</option>
            <option value="archive">Archives</option>
        </select>
        <button v-if="bulkSelection" :class="'btn btn-danger ' + deleteBtnState" @click="deleteMultiple">Delete
            Selected
        </button>
        <button :class="'btn btn-outline-info '" @click="bulkSelect">{{ bulkSelectionBtnText }}</button>
        <div class="search-box ms-auto ">
            <input class="search-control m-control" type="search" name="msearch" placeholder="Search here"
                   @change="filterByName"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "media-action",
    data: function () {
        return {
            bulkSelection: false,
            deleteBtnSt: false
        }
    },
    mounted() {
        const _this = this;
        this.$root.$on('chosen', function () {
            if (jQuery('.media-thumb.selected').length > 0) {
                _this.deleteBtnSt = true;
            } else {
                _this.deleteBtnSt = false;
            }
        });
    },
    methods: {
        listView: function (e) {
            e.preventDefault();
            // Highlight the button
            jQuery('.arrange').removeClass('active');
            jQuery(e.currentTarget).addClass('active');
            // Change the thumb view to list view
            this.$root.$emit('listView', e);

        },
        thumbView: function (e) {
            e.preventDefault();
            //  highlight the button
            jQuery('.arrange').removeClass('active');
            jQuery(e.currentTarget).addClass('active');
            // change the list view to thumb view
            this.$root.$emit('thumbView', e)
        },
        bulkSelect: function (e) {
            e.preventDefault();
            // Highlight the thumbs
            this.bulkSelection = !this.bulkSelection;
            this.$root.$emit('bulk-selection');
        },
        deleteMultiple: function () {
            this.$root.$emit('deleteMultiple');
        },
        filterMedia: function (e) {
            const type = jQuery(e.currentTarget).val();
            jQuery('.media-item').removeClass('d-none');
            if (type !== 'all') {
                jQuery('.media-resource').not('[data-type="' + type + '"]').parents('.media-item').addClass('d-none');
            }
        },
        filterByName: function (e) {
            const name = jQuery(e.currentTarget).val();
            jQuery('.media-item').removeClass('d-none');
            if(name !== '') {
                jQuery('.media-resource').each(function () {
                    let elemName = jQuery(this).attr('data-name');
                    let included = elemName.toLowerCase().includes(name.toLowerCase());
                    if (included == false) {
                        jQuery(this).parents('.media-item').addClass('d-none');
                    }
                });
            }
        }
    },
    computed: {
        bulkSelectionBtnText: function () {
            return (this.bulkSelection) ? "Cancel" : "Bulk Select";
        },
        deleteBtnState: function () {
            return this.deleteBtnSt ? '' : 'disabled';
        }
    }
}
</script>

<style scoped>

</style>