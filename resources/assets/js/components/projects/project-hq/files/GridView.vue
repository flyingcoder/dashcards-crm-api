<template>
    <div class="tab-pane fade tab-table" id="grid-view">
        <div class="grid-container" id="buzz-scroll">
            <div class="file-box" v-for="f in $parent.files" :key="f.id" :class="getGridClass(f.collection_name)" >
                <div class="image-container">
                    <img :src="f.thumb_url">
                </div>
                <div class="file-info">
                    <img :src="getImageType(f.collection_name)">
                    <span> {{ f.name }} </span>
                </div>
                <div class="user-info">
                    <img :src="'/' + f.custom_properties.user.image_url">
                    <label> Uploaded by 
                            <span> {{ f.custom_properties.user | fullname }} </span> 
                            <br> {{ f.created_at | momentAgo }} </label>
                </div>
                <grid-option></grid-option>
                <!-- <approval-option></approval-option> -->
            </div>
        </div>
        <!-- <div class="box-footer">
            <a href="#"> View More </a>
        </div> -->
    </div>
</template>

<script>
    import GridOption from './GridOption.vue';
    import ApprovalOption from './approval-option.vue';

    export default {
        components: {
          'grid-option': GridOption,
          'approval-option': ApprovalOption,
        },
        data(){
            return {
            }
        },
        methods: {
            getImageType(mime){
                switch(mime){
                    case 'project.files.documents':
                        return '/img/files/documents.svg';
                        break;
                    case 'project.files.images':
                        return '/img/files/image.svg';
                        break;
                    case  'project.files.videos':
                        return '/img/files/video.svg';                      
                        break;
                    case  'project.files.others':
                        return '/img/files/others.svg';                      
                        break;
                }
            },
            getGridClass(mime){
                switch(mime){
                    case 'project.files.documents':
                        return 'document';
                        break;
                    case 'project.files.images':
                        return '';
                        break;
                    case  'project.files.videos':
                        return 'video';                      
                        break;
                    case  'project.files.others':
                        return 'others';                      
                        break;
                }
            }
        }
    }
</script>