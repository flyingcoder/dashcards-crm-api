<template>
    <div class="tab-pane fade tab-table" id="grid-view">
        <div class="grid-container" id="buzz-scroll">
            <div class="file-box" >
                <div class="image-container">
                    <img src="img/temporary/sample.png">
                </div>
                <div class="file-info">
                    <img src="img/files/image.svg">
                    <span> Webbanner.jpg</span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user1.png">
                    <label> Uploaded by <span> Brian Howard </span> <br> 2hrs ago</label>
                </div>
                <grid-option></grid-option>
                <!-- <approval-option></approval-option> -->
            </div>
            <div class="file-box document">
                <div class="image-container">
                    <img src="img/files/documents-bw.svg">
                </div>
                <div class="file-info">
                    <img src="img/files/documents.svg">
                    <span> Questionnaire.doc </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user2.png">
                    <label> Uploaded by <span> William Stomhson</span> <br> 5hrs ago </label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box video">
                <div class="image-container">
                    <img src="img/temporary/sample.png">
                    <a href="#">
                        <img src="img/files/play-video.svg">
                    </a>
                </div>
                <div class="file-info">
                    <img src="img/files/video.svg">
                    <span> Web Intro.mp4 </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user3.png">
                    <label> Uploaded by <span> Tommy Jeff </span> <br> 1day ago</label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box others">
                <div class="image-container">
                    <img src="img/files/others-bw.svg">
                </div>
                <div class="file-info">
                    <img src="img/files/others.svg">
                    <span> Homepage.php </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user2.png">
                    <label> Uploaded by <span> Alan Podemski </span> <br> 5hrs ago</label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box">
                <div class="image-container">
                    <img src="img/temporary/sample.png">
                </div>
                <div class="file-info">
                    <img src="img/files/image.svg">
                    <span> Webbanner.jpg </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user1.png">
                    <label> Uploaded by <span> Brian Howard </span> <br> 2hrs ago</label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box document">
                <div class="image-container">
                    <img src="img/files/documents-bw.svg">
                </div>
                <div class="file-info">
                    <img src="img/files/documents.svg">
                    <span> Questionnaire.doc </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user2.png">
                    <label> Uploaded by <span> William Stomhson</span> <br> 5hrs ago</label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box video">
                <div class="image-container">
                    <img src="img/temporary/sample.png">
                    <a href="#">
                        <img src="img/files/play-video.svg">
                    </a>
                </div>
                <div class="file-info">
                    <img src="img/files/video.svg">
                    <span> Web Intro.mp4 </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user3.png">
                    <label> Uploaded by <span> Tommy Jeff </span> <br> 1day ago</label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box others">
                <div class="image-container">
                    <img src="img/files/others-bw.svg">
                </div>
                <div class="file-info">
                    <img src="img/files/others.svg">
                    <span> Homepage.php </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user2.png">
                    <label> Uploaded by <span> Alan Podemski </span> <br> 5hrs ago</label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box">
                <div class="image-container">
                    <img src="img/temporary/sample.png">
                </div>
                <div class="file-info">
                    <img src="img/files/image.svg">
                    <span> Webbanner.jpg </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user1.png">
                    <label> Uploaded by <span> Brian Howard </span> <br> 2hrs ago</label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box document">
                <div class="image-container">
                    <img src="img/files/documents-bw.svg">
                </div>
                <div class="file-info">
                    <img src="img/files/documents.svg">
                    <span> Questionnaire.doc </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user2.png">
                    <label> Uploaded by <span> William Stomhson</span> <br> 5hrs ago</label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box video">
                <div class="image-container">
                    <img src="img/temporary/sample.png">
                    <a href="#">
                        <img src="img/files/play-video.svg">
                    </a>
                </div>
                <div class="file-info">
                    <img src="img/files/video.svg">
                    <span> Web Intro.mp4 </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user3.png">
                    <label> Uploaded by <span> Tommy Jeff </span> <br> 1day ago</label>
                </div>
                <grid-option></grid-option>
            </div>
            <div class="file-box others">
                <div class="image-container">
                    <img src="img/files/others-bw.svg">
                </div>
                <div class="file-info">
                    <img src="img/files/others.svg">
                    <span> Homepage.php </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user2.png">
                    <label> Uploaded by <span> Alan Podemski </span> <br> 5hrs ago</label>
                </div>
                <grid-option></grid-option>
            </div>
                <div class="file-box others">
                <div class="image-container">
                    <img src="img/files/others-bw.svg">
                </div>
                <div class="file-info">
                    <img src="img/files/others.svg">
                    <span> Homepage.php </span>
                </div>
                <div class="user-info">
                    <img src="img/temporary/user2.png">
                    <label> Uploaded by <span> Alan Podemski </span> <br> 5hrs ago</label>
                </div>
                <grid-option></grid-option>
            </div>
        </div>
        <div class="box-footer">
            <a href="#"> View More </a>
        </div>
    </div>
</template>

<script>
    import GridOption from './grid-option.vue';
    import ApprovalOption from './approval-option.vue';

    export default {
        components: {
          'grid-option': GridOption,
          'approval-option': ApprovalOption,
        },
        data(){
            return {
            }
        }
    }
</script>