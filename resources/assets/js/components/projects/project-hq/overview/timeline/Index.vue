<template>
    <div class="box-content hq-timeline">
        <div class="timeline-content buzz-scrollbar" id="buzz-scroll">
            <div v-if="timelines.length == 0">
                <div class="nodata">
                    No activities available.
                </div>
            </div>
            <div v-if="timelines.length != 0">
                <div class="start"></div>
                <div class="timeline" v-for="timeline in timelines">
                    <div class="progress vertical"></div>
                    <div class="icon" v-if="checkIcon(timeline.properties.media.mime_type) == 'application'">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="18px" height="20px">
                            <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                            d="M1.848,2.893 L3.059,2.689 C3.043,2.806 3.035,2.927 3.035,3.049 L3.035,15.770 C3.035,16.532 3.346,17.226 3.845,17.729 L3.848,17.731 C4.348,18.234 5.037,18.546 5.795,18.546 L12.052,18.546 L4.671,19.793 C3.480,19.994 2.342,19.178 2.142,17.982 L0.047,5.436 C-0.153,4.238 0.658,3.094 1.848,2.893 L1.848,2.893 ZM5.795,0.841 C4.588,0.841 3.600,1.835 3.600,3.049 L3.600,15.770 C3.600,16.985 4.588,17.978 5.795,17.978 L14.821,17.978 C16.028,17.978 17.016,16.985 17.016,15.770 L17.016,4.486 L13.218,4.433 L13.218,0.841 L5.795,0.841 L5.795,0.841 ZM13.984,3.631 L13.984,0.841 L14.060,0.841 L17.016,3.502 L17.016,3.631 L13.984,3.631 Z"/>
                        </svg>
                    </div>
                    <div class="icon" v-if="checkIcon(timeline.properties.media.mime_type) == 'image'">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="22px" height="17px" style="left:-3px;">
                            <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                            d="M20.817,15.400 L4.514,15.400 C3.866,15.400 3.335,14.876 3.335,14.235 L3.335,2.092 C3.335,1.451 3.866,0.927 4.514,0.927 L20.817,0.927 C21.465,0.927 21.996,1.451 21.996,2.092 L21.996,14.235 C21.996,14.876 21.465,15.400 20.817,15.400 ZM20.808,2.222 L4.413,2.222 L4.413,10.463 L8.866,5.451 C9.407,4.842 9.846,5.013 10.348,5.608 L14.916,11.018 L17.219,8.712 C17.706,8.225 18.148,8.227 18.674,8.719 L20.808,10.717 L20.808,2.222 ZM17.427,6.858 C16.515,6.858 15.777,6.128 15.777,5.228 C15.777,4.328 16.515,3.599 17.427,3.599 C18.339,3.599 19.078,4.328 19.078,5.228 C19.078,6.128 18.339,6.858 17.427,6.858 ZM4.514,15.658 L13.038,15.658 L2.855,16.914 C2.211,16.994 1.619,16.539 1.539,15.903 L0.013,3.854 C-0.068,3.218 0.393,2.633 1.037,2.554 L3.074,2.302 L3.074,3.607 L1.100,3.851 L2.135,12.028 L3.074,10.661 L3.074,14.235 C3.074,14.626 3.236,14.982 3.497,15.240 C3.758,15.497 4.118,15.658 4.514,15.658 Z"/>
                        </svg>
                    </div>
                    <div class="date">
                        <span> {{ timeline.created_at | dateFilter }} <br> {{ timeline.created_at | getTime }} </span>
                    </div>
                    <div class="info">
                        <div class="title">
                            <span> {{ timeline.description }} </span>
                        </div>
                        <div class="image">
                            <img v-bind:src="timeline.properties.thumb_url"> 
                        </div>
                        <div class="type">
                            <span> {{ timeline.properties.media.file_name | minimize }} </span>
                        </div>
                        <!-- div class="progress">
                            <div class="progress-bar progress-success">
                            </div>
                        </div -->
                    </div>
                </div>
                <div class="start" style="top: -33px;"></div>
            </div>
        </div>
    </div>
</template>

<style>
.nodata {
    text-align: center;
    font-size: 20px;
}    
</style>

<script>
 export default {
     props: ['projectId'],
     data(){
         return {
             timelines: {},
             project_id: 0,
         }
     },
     mounted(){
        this.getTimeline();

     },
     methods:{
         getTimeline(){
             var vm = this;
             axios.get('/api/projects/'+this.projectId+'/timeline')
                 .then( response => {
                     vm.timelines = response.data;
                 })
                 .catch( error => {
                     if(error.response.status == 500 || error.response.status == 404){

                     }
                 });
         },
         checkIcon(mime_type){
            console.log(mime_type.split('/')[0]);
            return mime_type.split('/')[0];
         }
     },
     filters: {
        dateFilter: function (value) {

            moment.updateLocale('en', {
                calendar : {
                    lastDay : '[Yesterday]',
                    sameDay : '[Today]',
                    nextDay : '[Tomorrow]',
                    lastWeek : '[Last] dddd',
                    nextWeek : '[Next] dddd',
                    sameElse : 'L'
                }
            });

            return moment(value).calendar();
        },
        getTime: function (value) {
            return moment(value).format("hh:mm a");
        },
        minimize: function(value){
            let length = 10;
            let clamp = '...';
            var node = document.createElement('div');
            node.innerHTML = value;
            var content = node.textContent;
            return content.length > length ? content.slice(0, length) + clamp : content;
        }
     }
 }
</script>
<!-- template>
    <div class="box-content hq-timeline">
        <div class="timeline-content buzz-scrollbar" id="buzz-scroll">
            <div class="start"></div>
            <div class="timeline">
            <div class="progress vertical"></div>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="18px" height="20px">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M1.848,2.893 L3.059,2.689 C3.043,2.806 3.035,2.927 3.035,3.049 L3.035,15.770 C3.035,16.532 3.346,17.226 3.845,17.729 L3.848,17.731 C4.348,18.234 5.037,18.546 5.795,18.546 L12.052,18.546 L4.671,19.793 C3.480,19.994 2.342,19.178 2.142,17.982 L0.047,5.436 C-0.153,4.238 0.658,3.094 1.848,2.893 L1.848,2.893 ZM5.795,0.841 C4.588,0.841 3.600,1.835 3.600,3.049 L3.600,15.770 C3.600,16.985 4.588,17.978 5.795,17.978 L14.821,17.978 C16.028,17.978 17.016,16.985 17.016,15.770 L17.016,4.486 L13.218,4.433 L13.218,0.841 L5.795,0.841 L5.795,0.841 ZM13.984,3.631 L13.984,0.841 L14.060,0.841 L17.016,3.502 L17.016,3.631 L13.984,3.631 Z"/>
                </svg>
            </div>
            <div class="date">
                <span> Today <br> 12:00 am </span>
            </div>
            <div class="info">
                <div class="title">
                <span> Uploaded Questionnaire for Client xyz </span>
                </div>
                <div class="image">
                    <img src="/img/temporary/word.png"> 
                </div>
                <div class="type">
                <span> Questionnaire.doc </span>
                </div>
                <div class="progress">
                <div class="progress-bar progress-success">
                </div>
                </div>
            </div>
            </div>
            <div class="timeline">
            <div class="progress vertical"></div>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="22px" height="17px" style="left:-3px;">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M20.817,15.400 L4.514,15.400 C3.866,15.400 3.335,14.876 3.335,14.235 L3.335,2.092 C3.335,1.451 3.866,0.927 4.514,0.927 L20.817,0.927 C21.465,0.927 21.996,1.451 21.996,2.092 L21.996,14.235 C21.996,14.876 21.465,15.400 20.817,15.400 ZM20.808,2.222 L4.413,2.222 L4.413,10.463 L8.866,5.451 C9.407,4.842 9.846,5.013 10.348,5.608 L14.916,11.018 L17.219,8.712 C17.706,8.225 18.148,8.227 18.674,8.719 L20.808,10.717 L20.808,2.222 ZM17.427,6.858 C16.515,6.858 15.777,6.128 15.777,5.228 C15.777,4.328 16.515,3.599 17.427,3.599 C18.339,3.599 19.078,4.328 19.078,5.228 C19.078,6.128 18.339,6.858 17.427,6.858 ZM4.514,15.658 L13.038,15.658 L2.855,16.914 C2.211,16.994 1.619,16.539 1.539,15.903 L0.013,3.854 C-0.068,3.218 0.393,2.633 1.037,2.554 L3.074,2.302 L3.074,3.607 L1.100,3.851 L2.135,12.028 L3.074,10.661 L3.074,14.235 C3.074,14.626 3.236,14.982 3.497,15.240 C3.758,15.497 4.118,15.658 4.514,15.658 Z"/>
                </svg>
            </div>
            <div class="date">
                <span> Yesterday <br> 10:45 pm </span>
            </div>
            <div class="info">
                <div class="title">
                <span> Uploaded 24 files for Client x for following project </span>
                </div>
                <div class="image">
                    <img src="/img/temporary/one.png"> 
                    <img src="/img/temporary/two.png"> 
                    <img src="/img/temporary/three.png"> 
                </div>
                <div class="type">
                <span> Questionnaire.doc </span>
                </div>
                <div class="progress">
                <div class="progress-bar progress-success">
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template -->