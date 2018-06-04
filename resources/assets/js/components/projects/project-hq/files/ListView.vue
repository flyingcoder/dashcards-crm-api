<template>
    <div class="tab-pane fade tab-table active in" id="list-view">
        <table>
            <thead>
                <tr>
                    <th> 
                        <div class="table-checkbox">
                            <input type="checkbox" value="None" id="alltype" name="alltype" checked />
                            <label for="alltype"></label>
                        </div>
                    </th>
                    <th> Filetype </th>
                    <th> Filename </th>
                    <th> Uploaded by </th>
                    <th>  </th>
                </tr>
            </thead>
            <tbody id="buzz-scroll">
                <tr v-for="f in $parent.files" :key="f.id">
                    <td> 
                        <div class="table-checkbox">
                            <input type="checkbox" value="None" id="one" name="one" checked />
                            <label for="one"></label>
                        </div>
                    </td>  
                    <td> <img :src="getImageType(f.collection_name)"> </td>  
                    <td> <span> {{ f.file_name }} </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img :src="'/' + f.custom_properties.user.image_url">
                        </div>
                        <div class="user-info">
                            <span class="name">{{ f.custom_properties.user | fullname }}</span> <br>
                            <span class="time">{{ f.created_at | momentAgo }}</span>
                        </div>
                    </td>  
                    <td>
                        <list-option></list-option>
                    </td>  
                </tr>
            </tbody>
        </table>
        <!-- <div class="box-footer">
            <a href="#"> View More </a>
        </div> -->
    </div>
</template>

<script>
    import ListOption from './ListOption.vue';

    export default {
        components: {
          'list-option': ListOption,

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
                    default:
                        return '/img/files/documents.svg';
                        break;
                }
            }
        }
    }
</script>